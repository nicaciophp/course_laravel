<?php

namespace App\Http\Controllers;

use App\Payment\PagSeguro\CreditCard;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        //TODO VERIFICAR QUESTÃO DE CARACTERES ESPECIAIS NA HOREA DE EFETUAR O PAGAMENTO COM SKD
//        session()->forget('pagseguro_session_code');
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!session()->has('cart')) return redirect()->route('home');
        $this->makePagSeguroSession();

        $cartItems = array_map(function ($line){
           return $line['amount'] * $line['price'];
        }, session()->get('cart'));

        $cartItems = array_sum($cartItems);

//        dd(session()->get('cart'));
//        dd($user = auth()->user());

        return view('checkout',compact('cartItems'));
    }

    public function proccess(Request $request)
    {
        try {
            //TODO VERIFICAR QUESTÃO DE CARACTERES ESPECIAIS NA HOREA DE EFETUAR O PAGAMENTO COM SKD
            $dataPost = $request->all();
            $user = auth()->user();
            $cartItems = session()->get('cart');
            $reference = 'XPTO';
            $creditCardPayment = new CreditCard($cartItems, $user, $dataPost, $reference);
            $result = $creditCardPayment->doPayment();

            $userOrder = [
                'reference' => $reference,
                'pagseguro_code'=>$result->getCode(),
                'pagseguro_status'=>$result->getStatus(),
                'items'=>serialize($cartItems),
                'store_id'=>42
            ];

            $user->orders()->create($userOrder);

            session()->forget('cart');
            session()->forget('pagseguro_session_code');

            return response()->json([
                'data' => [
                    'status' => true,
                    'message' =>'Pedido criado com sucesso!',
                    'order' => $reference
                ]
            ]);
        } catch(\Exception $e){
            $message = env('APP_DEBUG') ? $e->getMessage() : 'Erro ao processar o pedido!';

            return response()->json([
                'data' => [
                    'status' => false,
                    'message' =>'Erro ao processar o pedido!'
                ]
            ], 401);
        }
    }

    public function thanks()
    {
        return view('thanks');
    }

    private function makePagSeguroSession()
    {
        if (!session()->has('pagseguro_session_code')) {
            $sessionCode = \PagSeguro\Services\Session::create(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );
            session()->put('pagseguro_session_code', $sessionCode->getResult());
        }

    }

}
