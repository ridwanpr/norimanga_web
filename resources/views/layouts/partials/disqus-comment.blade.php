<div id="disqus_thread"></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const disqus_config = function() {
                this.page.url = '{{ url()->current() }}';
                this.page.identifier = '{{ request()->path() }}'
            };

            const d = document,
                s = d.createElement('script');
            s.src = 'https://nori-3.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        }, 2000);
    });
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by
        Disqus.</a></noscript>
