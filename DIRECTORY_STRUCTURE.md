# Estrutura Completa de Diretórios - ETAPA 2

## Estrutura de Diretórios Final

```
theme/shiftclass/
├── amd/
│   ├── build/                    # Arquivos JS minificados (gerados automaticamente)
│   │   └── profiles_manager.min.js
│   └── src/                      # Arquivos JS fonte
│       └── profiles_manager.js
├── classes/
│   ├── datasource/
│   │   └── profile_datasource.php
│   ├── event/
│   │   ├── profile_created.php
│   │   ├── profile_deleted.php
│   │   └── profile_updated.php
│   ├── form/
│   │   └── profile_form.php
│   ├── output/
│   │   └── core_renderer.php
│   ├── privacy/
│   │   └── provider.php
│   └── profiles_manager.php
├── db/
│   ├── access.php
│   ├── caches.php
│   ├── events.php
│   ├── install.xml
│   ├── install.php
│   ├── services.php
│   ├── uninstall.php
│   └── upgrade.php
├── lang/
│   ├── en/
│   │   └── theme_shiftclass.php
│   └── pt_br/
│       └── theme_shiftclass.php
├── pix/
│   ├── screenshot.html
│   └── screenshot.jpg           # Gerar a partir do HTML
├── scss/
│   ├── core.scss
│   ├── custom.scss
│   ├── post.scss
│   └── pre.scss
├── styles/
│   └── profiles_admin.css
├── tests/
│   ├── behat/
│   │   ├── behat_theme_shiftclass.php
│   │   └── visual_profiles.feature
│   └── profiles_manager_test.php
├── ajax_profiles.php
├── config.php
├── delete_profile.php
├── edit_profile.php
├── externallib.php
├── lib.php
├── manage_profiles.php
├── settings.php
├── version.php
├── README.md                    # Da ETAPA 1
├── ETAPA1_COMPLETA.md
├── ETAPA2_COMPLETE.md          # Este documento
├── SCSS_COMPILATION.md
└── DIRECTORY_STRUCTURE.md      # Este arquivo
```

## Comandos para Criar a Estrutura

```bash
# Criar diretórios AMD
mkdir -p theme/shiftclass/amd/src
mkdir -p theme/shiftclass/amd/build

# Criar diretório de estilos
mkdir -p theme/shiftclass/styles

# Criar estrutura de classes
mkdir -p theme/shiftclass/classes/datasource
mkdir -p theme/shiftclass/classes/event
mkdir -p theme/shiftclass/classes/form
mkdir -p theme/shiftclass/classes/privacy

# Criar estrutura de testes
mkdir -p theme/shiftclass/tests/behat

# Copiar o arquivo JavaScript para AMD
cp profiles_manager.js theme/shiftclass/amd/src/

# Gerar build minificado (executar no Moodle)
php admin/cli/purge_caches.php
```

## Arquivos AMD/JavaScript

### Compilação AMD

O Moodle compila automaticamente os arquivos JavaScript AMD quando:
1. O modo de desenvolvimento está ativado: `$CFG->cachejs = false;`
2. Os caches são limpos
3. A página é recarregada

### Estrutura do Módulo AMD

```javascript
// amd/src/profiles_manager.js
define(['jquery', 'core/ajax', 'core/notification', ...], function($, Ajax, Notification, ...) {
    return {
        init: function() {
            // Código de inicialização
        }
    };
});
```

## Verificação da Instalação

### 1. Verificar Arquivos Principais
```bash
ls -la theme/shiftclass/classes/profiles_manager.php
ls -la theme/shiftclass/manage_profiles.php
ls -la theme/shiftclass/db/caches.php
```

### 2. Verificar JavaScript AMD
```bash
ls -la theme/shiftclass/amd/src/profiles_manager.js
# Após compilação:
ls -la theme/shiftclass/amd/build/profiles_manager.min.js
```

### 3. Limpar Caches
```bash
php admin/cli/purge_caches.php
```

### 4. Verificar no Navegador
1. Acessar: `/theme/shiftclass/manage_profiles.php`
2. Verificar console JavaScript para erros
3. Testar funcionalidades AJAX

## Permissões Necessárias

```bash
# Definir proprietário correto
chown -R www-data:www-data theme/shiftclass/

# Definir permissões
find theme/shiftclass/ -type d -exec chmod 755 {} \;
find theme/shiftclass/ -type f -exec chmod 644 {} \;
```

## Notas Importantes

1. **AMD Build**: Os arquivos `.min.js` são gerados automaticamente
2. **Cache**: Sempre limpar cache após mudanças em JavaScript
3. **Debugging**: Ativar `$CFG->debugdeveloper = true;` para ver erros
4. **SCSS**: Compilado automaticamente pelo Moodle
5. **Testes**: Executar com PHPUnit e Behat

## Conclusão

A estrutura está completa e pronta para a ETAPA 2. Todos os arquivos necessários foram criados e organizados seguindo os padrões do Moodle.