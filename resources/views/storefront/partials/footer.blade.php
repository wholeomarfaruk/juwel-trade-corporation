<footer class="jtc-footer">
    <div class="jtc-footer__inner">
        <div class="jtc-footer__cols">
            <div>
                <a href="#" class="jtc-footer__brand">
                    <span class="jtc-footer__mark"><img src="{{ asset('images/jtc-logo.jpeg') }}" alt="Juwel Trade Corporation"></span>
                    <span>
                        <span class="jtc-footer__name">Juwel Trade</span>
                        <span class="jtc-footer__sub">Corporation</span>
                    </span>
                </a>
                <div class="jtc-footer__blurb">
                    @if (!empty($site['footer_description']))
                        {!! $site['footer_description'] !!}
                    @else
                        Medical, physiotherapy and wellness equipment delivered across Bangladesh — quality gear from brands you trust.
                    @endif
                </div>
                {{-- <p class="jtc-footer__contact">
                    <strong>Call:</strong> 013 2973 2724<br>
                    <strong>Address:</strong> House 37, Road 4, Sector 4,<br>Uttara, Dhaka-1215, Bangladesh
                </p> --}}
            </div>
            <div>
                <h4>Useful links</h4>
                <ul>
                    <li><a href="#">Brands</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">All products</a></li>
                    <li><a href="{{ route('track.order.search') }}">Track order</a></li>
                </ul>
            </div>
            <div>
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Privacy policy</a></li>
                    <li><a href="#">Delivery policy</a></li>
                    <li><a href="#">Terms &amp; conditions</a></li>
                    <li><a href="#">Refund &amp; returns</a></li>
                </ul>
            </div>
            <div>
                <h4>Stay in the loop</h4>
                <p class="jtc-footer__blurb" style="max-width:none">Get early access to deals and new arrivals.</p>
                <form class="jtc-footer__news" @submit.prevent>
                    <input type="email" placeholder="Your email" aria-label="Email">
                    <button aria-label="Subscribe">@include('storefront.partials.icons.arrow')</button>
                </form>
                <div class="jtc-footer__pays">
                    <span class="jtc-footer__pay">VISA</span>
                    <span class="jtc-footer__pay">Mastercard</span>
                    <span class="jtc-footer__pay">bKash</span>
                    <span class="jtc-footer__pay">Nagad</span>
                    <span class="jtc-footer__pay">COD</span>
                </div>
            </div>
        </div>
        <div class="jtc-footer__bottom">
            <span>© {{ date('Y') }} Juwel Trade Corporation. All rights reserved.</span>
            <span>Developed by <a href="#">Ali Muzahid</a></span>
            <span>TRAD/DNCC/040005/2024</span>
        </div>
    </div>
</footer>
