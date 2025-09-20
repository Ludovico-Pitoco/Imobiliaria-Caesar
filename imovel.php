<?php
session_start();
require_once 'conexao.php';

$tipo = $_GET['tipo'] ?? '';
$id = intval($_GET['id'] ?? 0);

if (!$tipo || !$id) {
    echo "<p>Imóvel não encontrado.</p>";
    exit();
}

// Busca os dados do imóvel
switch($tipo) {
    case 'casa':
        $sql = "SELECT * FROM casa WHERE codcasa = $id";
        $tipo_exibicao = "Casa";
        break;
    case 'apartamento':
        $sql = "SELECT * FROM apartamento WHERE codapartamento = $id";
        $tipo_exibicao = "Apartamento";
        break;
    case 'terreno':
        $sql = "SELECT * FROM terreno WHERE codterreno = $id";
        $tipo_exibicao = "Terreno";
        break;
    default:
        echo "<p>Tipo de imóvel inválido.</p>";
        exit();
}

$result = $conn->query($sql);
if (!$result || $result->num_rows == 0) {
    echo "<p>Imóvel não encontrado.</p>";
    exit();
}

$imovel = $result->fetch_assoc();
$foto = !empty($imovel['foto']) ? "imagens/" . $imovel['foto'] : "imagens/image.png";
?>
<?php require_once('cabecalho.php'); ?>
<title><?= htmlspecialchars($imovel['nome']) ?> - Imobiliária</title>
</head>
<body>

<header>
    <div class="logo">Imobiliária Caesar</div>
    <div class="linha"></div><br>
    <nav>
        <a href="index.php">Início</a>
    </nav>
</header>

<main>
    <section class="property-detail" style="max-width:800px; margin:30px auto; padding:10px; text-align:center;">
        <h2 style="color:#ffffff; font-size:2.2em; margin-bottom:10px;"><?= htmlspecialchars($imovel['nome']) ?></h2>
        <p style="color:#f4d35e; font-weight:bold; font-size:1.5em; margin-bottom:20px;">Tipo: <?= $tipo_exibicao ?></p>
        <img src="<?= $foto ?>" alt="Imagem do imóvel" style="width:100%; max-width:600px; display:block; margin:0 auto 30px auto; object-fit:cover; border-radius:12px;">

        <?php if(!empty($imovel['descricao'])): ?>
            <p style="color:#f4d35e; font-size:1.2em; margin:15px 0;">
                <strong>Descrição:</strong> <?= htmlspecialchars($imovel['descricao']) ?>
            </p>
        <?php endif; ?>

        <?php if($tipo == 'casa' || $tipo == 'apartamento'): ?>
            
            <p style="color:#f4d35e; font-size:1.2em; margin:8px 0;"><strong>Localização:</strong> <?= htmlspecialchars($imovel['localizacao']) ?></p>
            <p style="color:#f4d35e; font-size:1.2em; margin:8px 0;"><strong>Tamanho:</strong> <?= htmlspecialchars($imovel['tamanho']) ?> m²</p>
            <p style="color:#f4d35e; font-size:1.2em; margin:8px 0;"><strong>Quartos:</strong> <?= $imovel['quartos'] ?></p>
            <p style="color:#f4d35e; font-size:1.2em; margin:8px 0;"><strong>Banheiros:</strong> <?= $imovel['banheiros'] ?></p>
            <p style="color:#f4d35e; font-size:1.2em; margin:8px 0;"><strong>Vagas:</strong> <?= $imovel['estacionamento'] ?></p>
            <p style="color:#f4d35e; font-size:1.2em; margin:8px 0;"><strong>Pets:</strong> <?= $imovel['pet'] ? 'Sim' : 'Não' ?></p>
            <?php if($tipo == 'apartamento'): ?>
                <p style="color:#f4d35e; font-size:1.2em; margin:8px 0;"><strong>Andar:</strong> <?= htmlspecialchars($imovel['andar']) ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p style="color:#f4d35e; font-size:1.2em; margin:8px 0;"><strong>Extensão:</strong> <?= htmlspecialchars($imovel['extensao']) ?> m²</p>
            <p style="color:#f4d35e; font-size:1.2em; margin:8px 0;"><strong>Localização:</strong> <?= htmlspecialchars($imovel['localizacao']) ?></p>
        <?php endif; ?>

        <p style="color:#f4d35e; font-size:1.3em; font-weight:bold; margin:15px 0;"><strong>Preço:</strong> R$ <?= $imovel['preco'] ?></p>

        <!-- Botão com verificação de login -->
        <form method="post" action="">
            <button type="submit" name="comprar_alugar" class="buttonormal">
                Comprar/Alugar
            </button>
        </form>

        <?php
        // Verifica se o botão foi clicado
        if(isset($_POST['comprar_alugar'])){
            if(isset($_SESSION['usuario'])){
                // Usuário logado - redireciona para página de compra/aluguel (a definir)
                echo "<script>alert('Comprado/Alugado com sucesso.');</script>";
            } else {
                // Usuário não logado - redireciona para login
                echo "<script>window.location.href='login.php';</script>";
            }
        }
        ?>
    </section>
</main>

</body>
</html>