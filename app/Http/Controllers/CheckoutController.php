<?php

namespace App\Http\Controllers;

use App\Payment\PagSeguro\CreditCard;
use App\Payment\PagSeguro\Notification;
use App\UserOrder;
use Illuminate\Http\Request;
use App\Store;
use Ramsey\Uuid\Uuid;

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
            $stores = array_unique(array_column($cartItems, 'store_id'));

            $reference = Uuid::uuid4();
            $creditCardPayment = new CreditCard($cartItems, $user, $dataPost, $reference);
            $result = $creditCardPayment->doPayment();

            $userOrder = [
                'reference' => $reference,
                'pagseguro_code'=>$result->getCode(),
                'pagseguro_status'=>$result->getStatus(),
                'items'=>serialize($cartItems),
            ];

            $userOrder = $user->orders()->create($userOrder);
            $userOrder->stores()->sync($stores);

//            //Notificar loja de novo pedido
            $store = (new Store())->notifyStoreOwners($stores);


            session()->forget('cart');
            session()->forget('pagseguro_session_code');

//            dd($stores);

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
                    'message' => $message
                ]
            ], 401);
        }
    }

    public function thanks()
    {
        return view('thanks');
    }

    public function notification()
    {
        try {
            $notification = new Notification();

            $notification = $notification->getTransaction();

            $reference = base64_decode($notification->getReference());

            //Atualizar o status do pedido exemplo de aguardando pagamento para pago
            $userOrder = UserOrder::whereReference($reference);
            $userOrder->update([
                'pagseguro_status'=> $notification->getStatus()
            ]);

//            //Comentarios sobre o pedido pago
//            if ($notification->getStatus() == 3){
//                //Liberar o pedido do usuário
//                //Notificar usuario que o pedido foi pago
//                //Nitificar a loja da confirmação do pedido
//            }
            return response()->json([], 203);
        } catch (\Exception $e){
            $message = env('APP_DEBUG') ? $e->getMessage() : '';
            return response()->json(['error' => $message], 500);
        }
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
