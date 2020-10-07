@extends('layouts.front')

@section('stylesheets')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endsection

@section('content')
    <div class="container">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h2>Dados para Pagamento</h2>
                    <hr>
                </div>
            </div>
        </div>
        <form action="" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-md-8">
                    <label for="">Nome do Cartão <span class="brand"></span></label>
                    <input type="text" name="card_name" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label for="">Número do Cartão <span class="brand"></span></label>
                    <input type="text" name="card_number" class="form-control">
                    <input type="hidden" name="card_brand">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="">Mês de Expiração</label>
                    <input type="text" name="card_month" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label for="">Ano de Expiração</label>
                    <input type="text" name="card_year" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-5">
                    <label for="">Código de segurança</label>
                    <input type="text" name="card_cvv" class="form-control">
                </div>
                <div class="col-md-8 installments form-group">

                </div>
            </div>
            <button class="btn btn-success btn-lg processCheckout">
                Efetuar Pagamento
            </button>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
    <script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        const sessionId = '{{session()->get('pagseguro_session_code')}}';
        const urlThanks = '{{route('checkout.thanks')}}';
        const urlProccess = '{{route("checkout.proccess")}}';
        const amountTransaction = '{{$cartItems}}';
        const csrf = '{{csrf_token()}}';
        PagSeguroDirectPayment.setSessionId(sessionId);
    </script>
    <script src="{{asset('js/pagseguro_functions.js')}}"></script>
    <script src="{{asset('js/pagseguro_events.js')}}"></script>
@endsection
