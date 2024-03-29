<!doctype html>
<html>
    <head>
        @include('includes.head')
    </head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        @include('includes.header')
    </nav>
    <div id="main" style="height: 100vh">
        @yield('content')
        <div class="btn-up btn-up_hide"> &#9650; </div>
    </div>
    <footer class="row">
        @include('includes.footer')
    </footer>
</div>
</body>

    <script>
        $(window).on('load', function() {
            $('#alertModal').modal({backdrop: 'static', keyboard: false}, 'show');
            var seenModal = getCookie('seen-modal');
            if (!seenModal) {
                $('#alertModal').modal('show');
                setCookie('seen-modal', true, 1);
            }
        });

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)===' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }

        function setCookie(name,value,days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }

        const btnUp = {
            el: document.querySelector('.btn-up'),
            show() {
                this.el.classList.remove('btn-up_hide');
            },
            hide() {
                this.el.classList.add('btn-up_hide');
            },
            addEventListener() {
                window.addEventListener('scroll', () => {
                    const scrollY = window.scrollY || document.documentElement.scrollTop;
                    scrollY > 0 ? this.show() : this.hide();
                });
                document.querySelector('.btn-up').onclick = () => {
                    window.scrollTo({
                        top: 0,
                        left: 0,
                        behavior: 'smooth'
                    });
                }
            }
        };

        btnUp.addEventListener();
    </script>

</html>
