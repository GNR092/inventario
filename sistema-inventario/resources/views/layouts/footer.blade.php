<footer class="mb-footer">
    <div class="footer-content">
        © 2026 MB Signature Properties
    </div>
</footer>

<style>
.mb-footer{
    width:100%;
    padding:20px 0;
    text-align:center;
    font-size:13px;
    letter-spacing:.6px;

    background:rgba(15,23,42,.85);
    border-top:1px solid rgba(212,175,55,.25);
    backdrop-filter:blur(12px);

    color:var(--color-carbon-400);

    position:relative;
    z-index:2;
}

.footer-content{
    max-width:1200px;
    margin:auto;
}

.mb-footer::before{
    content:"";
    position:absolute;
    top:0;
    left:50%;
    transform:translateX(-50%);
    width:140px;
    height:1px;
    background:linear-gradient(to right,#d4af37,#f7e7a9);
}
</style>

 <style>
        body{
            min-height:100vh;
            display:flex;
            flex-direction:column;
            background-color:var(--color-carbon-950);
            color:var(--color-carbon-100);
        }

        main{
            flex:1;
        }
    </style>
