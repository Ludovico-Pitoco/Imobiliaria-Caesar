<?php 
session_start();
require_once('cabecalho.php'); 
require_once('conexao.php'); 
?>
<title>Início - Imobiliária</title>
</head>
<body>

<header>
    <div class="logo">Imobiliária Caesar</div>
    <div class="linha"></div><br>
    <nav style="display:flex; justify-content: space-between; align-items:center;">
        <div>
            <a href="index.php">Início</a>
            <a href="anunciar.php">Anunciar</a>
        </div>
        <div>
            <?php if(isset($_SESSION['usuario'])): ?>
                <a href="logout.php" style="background-color: rgb(107, 19, 81); color:white; padding:8px 15px; border-radius:4px; text-decoration:none;">Sair</a>
            <?php else: ?>
                <a href="login.php" style="background-color: rgb(107, 19, 81); color:white; padding:8px 15px; border-radius:4px; text-decoration:none;">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main>
    <section class="featured">
        <h2>Imóveis em Destaque</h2>
        <div class="property-list">
            <?php
            function mostrarImoveis($conn, $tabela, $campo_id, $tipo) {
                $sql = "SELECT * FROM $tabela";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = $row[$campo_id];
                        $nome = $row['nome'];
                        $localizacao = $row['localizacao'];
                        $preco = $row['preco'];
                        $foto = $row['foto'] ?? 'image.png'; 
                        $imagem = "imagens/" . $foto;

                        echo "<a href='imovel.php?tipo=$tipo&id=$id' class='property-link'>";
                        echo '<div class="property-card">';
                        echo "<img src='$imagem' alt='Imagem do imóvel'>";
                        echo "<div class='card-info'>";
                        echo "<h3>$nome</h3>";
                        echo "<p>$localizacao</p>";
                        echo "<p>R$ $preco</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</a>";
                    }
                }
            }

            mostrarImoveis($conn, "casa", "codcasa", "casa");
            mostrarImoveis($conn, "apartamento", "codapartamento", "apartamento");
            mostrarImoveis($conn, "terreno", "codterreno", "terreno");
            ?>
        </div>
    </section>
</main>

<style>
.property-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.property-link {
    text-decoration: none;
    color: inherit;
}

.property-card {
    width: 250px;
    height: 300px; 
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    text-align: center;
    transition: transform 0.2s;
}

.property-card:hover {
    transform: translateY(-5px);
}

.property-card img {
    width: 100%;
    height: 70%; 
    object-fit: cover; 
}

.card-info {
    padding: 5px;
    height: 30%; 
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.card-info h3 {
    margin: 2px 0;
    font-size: 1.1em;
    color: #333;
}

.card-info p {
    margin: 1px 0;
    font-size: 0.9em;
    color: #555;
}
</style>

</body>
</html>