<?php
require_once(__DIR__ . '/../../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance());

echo "Teste 1: Config carregado<br>";

// Testa se a classe existe
$classfile = __DIR__ . '/classes/profiles_manager.php';
if (file_exists($classfile)) {
    echo "Teste 2: Arquivo profiles_manager.php existe<br>";
    require_once($classfile);
    
    if (class_exists('\theme_shiftclass\profiles_manager')) {
        echo "Teste 3: Classe profiles_manager existe<br>";
        
        try {
            $manager = new \theme_shiftclass\profiles_manager();
            echo "Teste 4: Instância criada com sucesso<br>";
        } catch (Exception $e) {
            echo "Erro ao criar instância: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Erro: Classe não encontrada<br>";
    }
} else {
    echo "Erro: Arquivo não encontrado em: $classfile<br>";
}

// Testa banco de dados
global $DB;
try {
    $tables = $DB->get_tables();
    $shiftclass_tables = array_filter($tables, function($table) {
        return strpos($table, 'shiftclass') !== false;
    });
    echo "Teste 5: Tabelas ShiftClass encontradas: " . implode(', ', $shiftclass_tables) . "<br>";
} catch (Exception $e) {
    echo "Erro ao verificar tabelas: " . $e->getMessage() . "<br>";
}