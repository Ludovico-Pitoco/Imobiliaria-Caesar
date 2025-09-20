<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once "conexao.php";

$mensagem = "";
$erros = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tipo = $_POST['tipo']; // casa, apartamento ou terreno
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $localizacao = trim($_POST['localizacao']);
    $preco = trim($_POST['preco']);

    if (empty($nome)) $erros[] = "O nome do imóvel é obrigatório.";
    if (empty($descricao)) $erros[] = "A descrição é obrigatória.";
    if (empty($localizacao)) $erros[] = "A localização é obrigatória.";
    if (empty($preco) || !is_numeric($preco) || floatval($preco) <= 0) $erros[] = "O preço deve ser positivo.";

    // Campos específicos
    if ($tipo == "casa") {
        $tamanho = trim($_POST['tamanho_casa']);
        $quartos = intval($_POST['quartos_casa'] ?? 0);
        $banheiros = intval($_POST['banheiros_casa'] ?? 0);
        $estacionamento = intval($_POST['estacionamento_casa'] ?? 0);
        $pet = isset($_POST['pet_casa']) ? 1 : 0;
        if (empty($tamanho)) $erros[] = "O tamanho é obrigatório para casas.";
    }

    if ($tipo == "apartamento") {
        $tamanho = trim($_POST['tamanho_apt']);
        $quartos = intval($_POST['quartos_apt'] ?? 0);
        $banheiros = intval($_POST['banheiros_apt'] ?? 0);
        $estacionamento = intval($_POST['estacionamento_apt'] ?? 0);
        $pet = isset($_POST['pet_apt']) ? 1 : 0;
        $andar = trim($_POST['andar']);
        if (empty($tamanho)) $erros[] = "O tamanho é obrigatório para apartamentos.";
        if (empty($andar)) $erros[] = "O andar é obrigatório para apartamentos.";
    }

    if ($tipo == "terreno") {
        $extensao = trim($_POST['extensao']);
        if (empty($extensao)) $erros[] = "A extensão é obrigatória para terrenos.";
    }

    // Upload da primeira foto
    $foto_nome = null;
    if (!empty($_FILES['fotos']['name'][0])) {
        $diretorio = "imagens/";
        $arquivo = uniqid() . "_" . basename($_FILES['fotos']['name'][0]);
        $caminho_foto = $diretorio . $arquivo;
        $tipo_arquivo = strtolower(pathinfo($caminho_foto, PATHINFO_EXTENSION));

        if (!in_array($tipo_arquivo, ["jpg","jpeg","png","gif"])) {
            $erros[] = "Apenas JPG, JPEG, PNG ou GIF são permitidos.";
        } elseif (!move_uploaded_file($_FILES['fotos']['tmp_name'][0], $caminho_foto)) {
            $erros[] = "Erro ao salvar a imagem.";
        } else {
            $foto_nome = $arquivo; // salvamos só o nome
        }
    }

    if (empty($erros)) {
        if ($tipo == "casa") {
            $sql = "INSERT INTO casa (nome, descricao, localizacao, tamanho, quartos, banheiros, estacionamento, pet, preco, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiiiisss", $nome, $descricao, $localizacao, $tamanho, $quartos, $banheiros, $estacionamento, $pet, $preco, $foto_nome);
        } elseif ($tipo == "apartamento") {
            $sql = "INSERT INTO apartamento (nome, descricao, localizacao, tamanho, quartos, banheiros, estacionamento, pet, andar, preco, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiiiiisss", $nome, $descricao, $localizacao, $tamanho, $quartos, $banheiros, $estacionamento, $pet, $andar, $preco, $foto_nome);
        } else { // terreno
            $sql = "INSERT INTO terreno (nome, descricao, localizacao, extensao, preco, foto) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $nome, $descricao, $localizacao, $extensao, $preco, $foto_nome);
        }

        if ($stmt->execute()) {
            $mensagem = "<p style='color:green;'>Imóvel cadastrado com sucesso!</p>";
        } else {
            $erros[] = "Erro ao cadastrar imóvel: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<?php require_once('cabecalho.php'); ?>
<title>Anunciar - Imobiliária</title>
</head>
<main>
<section class="property-form">
    <h2>Anunciar Imóvel</h2>

    <?php
    if (!empty($mensagem)) echo $mensagem;
    if (!empty($erros)) {
        foreach($erros as $erro) echo "<p style='color:red;'>$erro</p>";
    }
    ?>

    <form method="post" enctype="multipart/form-data">
        <label for="tipo">Tipo de Imóvel</label>
        <select id="tipo" name="tipo" required>
            <option value="">Selecione...</option>
            <option value="casa" <?= ($_POST['tipo'] ?? '')=='casa'?'selected':'' ?>>Casa</option>
            <option value="apartamento" <?= ($_POST['tipo'] ?? '')=='apartamento'?'selected':'' ?>>Apartamento</option>
            <option value="terreno" <?= ($_POST['tipo'] ?? '')=='terreno'?'selected':'' ?>>Terreno</option>
        </select>

        <input type="text" name="nome" placeholder="Título do imóvel" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
        <textarea name="descricao" placeholder="Descrição do imóvel" required><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
        <input type="text" name="localizacao" placeholder="Localização" value="<?= htmlspecialchars($_POST['localizacao'] ?? '') ?>" required>
        <input type="number" name="preco" placeholder="Preço" value="<?= htmlspecialchars($_POST['preco'] ?? '') ?>" required>
        <input type="file" name="fotos[]" required>

        <!-- Campos específicos -->
        <div id="casa-campos" class="extra-fields" style="display:none;">
            <input type="number" name="quartos_casa" placeholder="Número de Quartos" value="<?= htmlspecialchars($_POST['quartos_casa'] ?? '') ?>">
            <input type="number" name="banheiros_casa" placeholder="Número de Banheiros" value="<?= htmlspecialchars($_POST['banheiros_casa'] ?? '') ?>">
            <input type="number" name="estacionamento_casa" placeholder="Vagas de Estacionamento" value="<?= htmlspecialchars($_POST['estacionamento_casa'] ?? '') ?>">
            <label><input type="checkbox" name="pet_casa" <?= isset($_POST['pet_casa'])?'checked':'' ?>> Aceita Pets</label>
            <input type="text" name="tamanho_casa" placeholder="Tamanho (m²)" value="<?= htmlspecialchars($_POST['tamanho_casa'] ?? '') ?>">
        </div>

        <div id="apartamento-campos" class="extra-fields" style="display:none;">
            <input type="number" name="quartos_apt" placeholder="Número de Quartos" value="<?= htmlspecialchars($_POST['quartos_apt'] ?? '') ?>">
            <input type="number" name="banheiros_apt" placeholder="Número de Banheiros" value="<?= htmlspecialchars($_POST['banheiros_apt'] ?? '') ?>">
            <input type="number" name="estacionamento_apt" placeholder="Vagas de Estacionamento" value="<?= htmlspecialchars($_POST['estacionamento_apt'] ?? '') ?>">
            <label><input type="checkbox" name="pet_apt" <?= isset($_POST['pet_apt'])?'checked':'' ?>> Aceita Pets</label>
            <input type="text" name="tamanho_apt" placeholder="Tamanho (m²)" value="<?= htmlspecialchars($_POST['tamanho_apt'] ?? '') ?>">
            <input type="text" name="andar" placeholder="Andar" value="<?= htmlspecialchars($_POST['andar'] ?? '') ?>">
        </div>

        <div id="terreno-campos" class="extra-fields" style="display:none;">
            <input type="text" name="extensao" placeholder="Extensão (m²)" value="<?= htmlspecialchars($_POST['extensao'] ?? '') ?>">
        </div>

        <button type="submit">Anunciar</button>
    </form>

    <a href="index.php" class="btn-voltar">← Voltar ao Início</a>
</section>
</main>

<script>
const tipoSelect = document.getElementById("tipo");
const casaCampos = document.getElementById("casa-campos");
const apartamentoCampos = document.getElementById("apartamento-campos");
const terrenoCampos = document.getElementById("terreno-campos");

function mostrarCampos() {
    casaCampos.style.display = "none";
    apartamentoCampos.style.display = "none";
    terrenoCampos.style.display = "none";

    if (tipoSelect.value === "casa") casaCampos.style.display = "block";
    if (tipoSelect.value === "apartamento") apartamentoCampos.style.display = "block";
    if (tipoSelect.value === "terreno") terrenoCampos.style.display = "block";
}

tipoSelect.addEventListener("change", mostrarCampos);
window.addEventListener("load", mostrarCampos);
</script>
</body>
</html>