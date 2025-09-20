<?php
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tipo = $_POST['tipo']; // casa, apartamento ou terreno
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $localizacao = trim($_POST['localizacao']);
    $preco = trim($_POST['preco']);

    $erros = [];

    if (empty($nome)) $erros[] = "O nome do imóvel é obrigatório.";
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

    // Upload da primeira foto (opcional)
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
            $sql = "INSERT INTO casa (nome, localizacao, tamanho, quartos, banheiros, estacionamento, pet, preco, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiiiiss", $nome, $localizacao, $tamanho, $quartos, $banheiros, $estacionamento, $pet, $preco, $foto_nome);
        } elseif ($tipo == "apartamento") {
            $sql = "INSERT INTO apartamento (nome, localizacao, tamanho, quartos, banheiros, estacionamento, pet, andar, preco, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiiiiiss", $nome, $localizacao, $tamanho, $quartos, $banheiros, $estacionamento, $pet, $andar, $preco, $foto_nome);
        } else { // terreno
            $sql = "INSERT INTO terreno (nome, localizacao, extensao, preco, foto) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nome, $localizacao, $extensao, $preco, $foto_nome);
        }

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Imóvel cadastrado com sucesso!</p>";
        } else {
            echo "<p style='color:red;'>Erro ao cadastrar imóvel: ".$stmt->error."</p>";
        }

        $stmt->close();
    } else {
        foreach($erros as $erro){
            echo "<p style='color:red;'>$erro</p>";
        }
    }
}

$conn->close();
?>