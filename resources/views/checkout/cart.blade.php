@extends('user')
@section('title', 'Cart')
@section('page-contents')

<!-- Page Content -->
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Keranjang Pesanan</h1>
        <span>Pilih Pesanan Anda</span>
      </div>
    </div>
  </div>
</div>

<div class="contact-information2">
  @if (count($errors) > 0)
  <div class="container alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <form action="{{ route('cart.checkout') }}" method="post">
            @csrf
            @method('POST')
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col"> </th>
                  <th scope="col" class="text-left">Product</th>
                  <th scope="col" class="text-center">Price</th>
                  <th scope="col" class="text-center">Quantity</th>
                  <th scope="col" class="text-center">Total Price</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @if(empty($cart) || count($cart) == 0)
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                @else
                @php $grandtotal = 0 @endphp
                @foreach ($cart as $item )
                @php $subtotal = $item->product->price * $item->qty @endphp
                @php $image = DB::table('product_images')->where('product_id', '=' , $item->product_id)->get(); @endphp
                <tr>
                  <td class="text-center"><input type="checkbox" name="product_id[]" id="checkme"
                      value="{{ $item->product_id }}"></td>
                  <td><img src="{{ asset('product_images/'.$image[0]->image_name) }}" height="45" /> </td>
                  <td>{{ $item->product->product_name }}</td>
                  <td class="text-center">{{ "Rp" . number_format($item->product->price, 0, ",", ",") }}</td>
                  <td class="text-center">{{ $item->qty }}</td>
                  <td class="text-center">{{ "Rp" . number_format($subtotal, 0, ",", ",") }}</td>
                  <td class="text-center"><button class="btn btn-sm btn-danger"><a
                        href="{{URL::to('cart/'.$item->product_id)}}" style="text-decoration: none; color: inherit;"><i
                          class="fa fa-trash"></i> </a> </button> </td>
                </tr>
                @php $grandtotal+=$subtotal @endphp
                @endforeach
                @endif
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="text-center"><strong>Sub-Total</strong></td>
                  <td class="text-center"><strong>@if (count($cart) > 0)
                      {{ "Rp" . number_format($grandtotal, 0, ",", ",") }} @else Rp. 0 @endif</strong></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
            <div class="col mb-2">
              <div class="row">
                <div class="col-sm-12  col-md-6">
                  <button class="btn btn-lg btn-block btn-warning"><a style="text-decoration: none; color: inherit;"
                      href="{{ url('home') }}">Continue Shopping</a></button>
                </div>
                <div class="col-sm-12 col-md-6 text-right">
                  <button class="btn btn-lg btn-block btn-success text-uppercase" type="submit"
                    id="checkout">Checkout</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer Starts Here -->
@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript">
  function initthis()
{
document.getElementById("checkout").disabled=true;
}

function dis_enable_submit()
{
if(document.getElementById("checkme").checked >0)
{
document.getElementById("checkout").disabled=false;
}
else
{
document.getElementById("checkout").disabled=true;
}
}

window.onload=initthis;
</script>