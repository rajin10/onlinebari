<script src="{{ asset('/') }}assets/frontend/js/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="{{ asset('/') }}assets/frontend/js/bootstrap.min.js"></script>
<script src="{{ asset('/') }}assets/frontend/js/slick.js"></script>
<script src="{{ asset('/') }}assets/frontend/js/moment.js"></script>
<script src="{{ asset('/') }}assets/frontend/js/toast.min.js"></script>
<script src="{{ asset('/') }}assets/frontend/js/Font-Awesome.js"></script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
{{--  <x-notify::notify /> --}}
@notifyJs
@stack('js')
<script src="{{ asset('/') }}assets/frontend/js/main.js"></script>
<input type="hidden" value="{{ route('product.advance-search') }}" id="aurl" name="">
<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,bn',
            autoDisplay: false,
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }

    function foo() {
        $("option[value='en']").text("عربى (Arabic)");
        $("option[value='fr']").text("Français (French)");
        //.... so on
    }
    const texts = [
        // "{!! setting('placeholder_one') !!}', '{!! setting('placeholder_two') !!}', '{!! setting('placeholder_three') !!}', '{!! setting('placeholder_four') !!}"
        "Enter Your Keywords"
    ];
    const input = document.querySelector('#searchbox');
    const animationWorker = function(input, texts) {
        this.input = input;
        this.defaultPlaceholder = this.input.getAttribute('placeholder');
        this.texts = texts;
        this.curTextNum = 0;
        this.curPlaceholder = '';
        this.blinkCounter = 0;
        this.animationFrameId = 0;
        this.animationActive = false;
        this.input.setAttribute('placeholder', this.curPlaceholder);

        this.switch = timeout => {
            this.input.classList.add('imitatefocus');
            setTimeout(
                () => {
                    this.input.classList.remove('imitatefocus');
                    if (this.curTextNum == 0)
                        this.input.setAttribute('placeholder', this.defaultPlaceholder);
                    else

                        this.input.setAttribute('placeholder', this.curPlaceholder);

                    setTimeout(
                        () => {
                            this.input.setAttribute('placeholder', this.curPlaceholder);
                            if (this.animationActive)
                                this.animationFrameId = window.requestAnimationFrame(this.animate);
                        },
                        timeout);
                },
                timeout);
        };

        this.animate = () => {
            if (!this.animationActive) return;
            let curPlaceholderFullText = this.texts[this.curTextNum];
            let timeout = 10;
            if (this.curPlaceholder == curPlaceholderFullText + '|' && this.blinkCounter == 3) {
                this.blinkCounter = 0;
                this.curTextNum = this.curTextNum >= this.texts.length - 1 ? 0 : this.curTextNum + 1;
                this.curPlaceholder = '|';
                this.switch(2000);
                return;
            } else
            if (this.curPlaceholder == curPlaceholderFullText + '|' && this.blinkCounter < 3) {
                this.curPlaceholder = curPlaceholderFullText;
                this.blinkCounter++;
            } else
            if (this.curPlaceholder == curPlaceholderFullText && this.blinkCounter < 3) {
                this.curPlaceholder = this.curPlaceholder + '|';
            } else {
                this.curPlaceholder = curPlaceholderFullText.
                split('').
                slice(0, this.curPlaceholder.length + 1).
                join('') + '|';
                timeout = 150;
            }
            this.input.setAttribute('placeholder', this.curPlaceholder);
            setTimeout(
                () => {
                    if (this.animationActive) this.animationFrameId = window.requestAnimationFrame(this
                        .animate);
                },
                timeout);
        };

        this.stop = () => {
            this.animationActive = false;
            window.cancelAnimationFrame(this.animationFrameId);
        };

        this.start = () => {
            this.animationActive = true;
            this.animationFrameId = window.requestAnimationFrame(this.animate);
            return this;
        };
    };

    document.addEventListener("DOMContentLoaded", () => {
        let aw = new animationWorker(input, texts).start();
        input.addEventListener("focus", e => aw.stop());
        input.addEventListener("blur", e => {
            aw = new animationWorker(input, texts);
            if (e.target.value == '') setTimeout(aw.start, 1000);
        });
    });

    setInterval(function() {
        $('.notify').hide();
    }, 5000);

    const debounce = (func, delay) => {
        let debounceTimer
        return function() {
            const context = this
            const args = arguments
            clearTimeout(debounceTimer)

            debounceTimer = setTimeout(() => {
                func.apply(context, args)
            }, delay)
        }
    }

    // search view
    const getSearchResultHtml = (products) => {
        let html = '';

        if (!products.length) {
            return `
                <div class="product col-lg-12" style="height: initial;">
                    <div class="product-wrapper list-comp">
                        <div class="pin"
                            style="display: flex; margin-bottom: 0; background: white; padding: 5px; border-bottom: 1px solid gainsboro;">
                            <div class="details">
                                <h5 style="font-size: 15px">No products found</h5>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        products.forEach(product => {
            html += `
                <div class="product col-lg-12" style="height: initial;">
                    <div class="product-wrapper list-comp">
                        <a href="/product/${product.slug}">
                            <div class="pin"
                                style="display: flex; margin-bottom: 0; background: white; padding: 5px; border-bottom: 1px solid gainsboro;">
                                <div class="thumbnail" style="margin-top: 0px;">
                                    <img style="object-fit: fill; width: 60px; height: 60px; max-width: 100px;"
                                        src="${`/uploads/product/${product.image}`}"
                                        alt="${product.title}">
                                </div>
                                <div class="details" style="padding-left: 10px; padding-top: 0px !important;">
                                    <h5 style="font-size: 15px">${product.title}</h5>
                                    <h5 style="font-size: 15px">
                                        Price :
                                        (${product.discount_price ? 
                                            `<del style="color: gray;">Tk.${product.regular_price}</del> Tk. ${product.discount_price}` :
                                            `${product.regular_price}`
                                        })
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            `
        });

        return html;
    }

    // convert the code to fetch
    const debouncedProductSearch = debounce(async function () {
        let key = $('#searchbox').val();
        let url = $('#aurl').val();
        let searchResult = document.getElementById('search-results');

        console.log({ key, url, searchResult })

        if (key.length < 3) {
            searchResult.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    key: key
                })
            });

            const data = await response.json();
            searchResult.innerHTML = getSearchResultHtml(data.products);
        } catch (error) {
            console.log(error);
        }
    }, 300);

    $(document).on('input', '#searchbox', debouncedProductSearch);
</script>
<!-- GLOBAL JS/ INTERNAL JS _@stack('internal_js') -->
<script type="text/javascript">
    @php
        echo setting('global_js');
    @endphp
    @stack('internal_js')
</script>
