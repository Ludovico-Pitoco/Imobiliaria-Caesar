<?php require_once('cabecalho.php');?>
    <title>Anunciar - Imobiliária</title>
</head>
<main>
    <section class="property-form">
        <h2>Anunciar Imóvel</h2>
        <form>
            <input type="text" placeholder="Título do imóvel" required>
            <textarea placeholder="Descrição do imóvel" required></textarea>
            <input type="text" placeholder="Localização" required>
            <input type="number" placeholder="Preço" required>
            <input type="number" placeholder="Número de Quartos" required>
            <input type="file" placeholder="Fotos" multiple required>
            <button type="submit">Anunciar</button>
        </form>
    </section>
</main>
</body>

</html>