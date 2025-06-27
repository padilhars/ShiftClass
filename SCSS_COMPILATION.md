# Sistema de Compilação SCSS - ShiftClass Theme

## Visão Geral

O tema ShiftClass implementa um sistema robusto de compilação SCSS que garante o carregamento e processamento correto dos arquivos `pre.scss` e `post.scss`, conforme solicitado nos requisitos da ETAPA 1.

## Arquitetura de Compilação

### 1. Configuração Principal (config.php)

```php
// Callback principal para compilação SCSS
$THEME->scss = function($theme) {
    return theme_shiftclass_get_main_scss_content($theme);
};

// Callbacks para pre e post SCSS
$THEME->prescsscallback = 'theme_shiftclass_get_pre_scss';
$THEME->extrascsscallback = 'theme_shiftclass_get_extra_scss';
```

### 2. Ordem de Processamento

O Moodle processa os arquivos SCSS na seguinte ordem:

1. **Pre SCSS** (`theme_shiftclass_get_pre_scss()`)
   - Variáveis de configuração do tema
   - Conteúdo de `scss/pre.scss`
   - SCSS customizado do administrador (campo scsspre)

2. **Main SCSS** (`theme_shiftclass_get_main_scss_content()`)
   - Preset do Boost (default.scss ou plain.scss)
   - `scss/core.scss`
   - `scss/custom.scss`

3. **Extra SCSS** (`theme_shiftclass_get_extra_scss()`)
   - `scss/post.scss`
   - SCSS customizado do administrador (campo scss)

### 3. Implementação Detalhada

#### Pre SCSS (pre.scss)
```scss
// Define variáveis CSS customizadas
:root {
    --shiftclass-primary: #{$primary};
    --shiftclass-secondary: #{$secondary};
    --shiftclass-background: #{$white};
}

// Mixins reutilizáveis
@mixin shiftclass-profile-colors() {
    // Aplicação dinâmica de cores
}
```

**Função no lib.php:**
```php
function theme_shiftclass_get_pre_scss($theme) {
    // 1. Injeta variáveis de configuração
    // 2. Carrega pre.scss
    // 3. Adiciona SCSS customizado do admin
}
```

#### Post SCSS (post.scss)
```scss
// Aplica estilos usando variáveis e mixins definidos
body {
    @include shiftclass-profile-colors();
}

// Estilos customizados do tema
.shiftclass-course-header {
    @include shiftclass-course-header();
}
```

**Função no lib.php:**
```php
function theme_shiftclass_get_extra_scss($theme) {
    // 1. Carrega post.scss
    // 2. Adiciona SCSS customizado do admin
}
```

## Fluxo de Compilação Completo

```
┌─────────────────────┐
│  Início Compilação  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│    PRE SCSS         │
├─────────────────────┤
│ • Variáveis Config  │
│ • pre.scss          │
│ • Admin Pre SCSS    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│    MAIN SCSS        │
├─────────────────────┤
│ • Boost Preset      │
│ • core.scss         │
│ • custom.scss       │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│    EXTRA SCSS       │
├─────────────────────┤
│ • post.scss         │
│ • Admin Extra SCSS  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│   CSS Compilado     │
└─────────────────────┘
```

## Garantias de Compilação

### 1. Carregamento de Arquivos
- Uso de `file_get_contents()` com caminho absoluto
- Verificação de existência antes do carregamento
- Fallback para conteúdo vazio se arquivo não existir

### 2. Cache e Performance
- `theme_reset_all_caches()` chamado após mudanças
- Compilação apenas quando necessário
- Cache automático do CSS compilado

### 3. Ordem de Precedência
1. Variáveis definidas em pre.scss
2. Estilos do Boost
3. Customizações em post.scss
4. SCSS do administrador (máxima prioridade)

## Uso de Variáveis CSS

### Definição (pre.scss)
```scss
:root {
    --shiftclass-primary: #0f6cbf;
    --shiftclass-secondary: #6c757d;
    --shiftclass-background: #ffffff;
}
```

### Aplicação (post.scss)
```scss
.navbar {
    background-color: var(--shiftclass-primary);
}

.btn-primary {
    background-color: var(--shiftclass-primary);
    border-color: var(--shiftclass-primary);
}
```

## Integração com Perfis Visuais

Embora a implementação completa dos perfis visuais seja para a ETAPA 2, a estrutura está preparada:

1. **Variáveis CSS** permitem alteração dinâmica de cores
2. **Mixins** facilitam aplicação consistente de estilos
3. **Renderer** pode injetar CSS adicional baseado no perfil

## Debugging e Troubleshooting

### Verificar Compilação
1. Ative o modo de desenvolvimento: `$CFG->themdesignermode = true;`
2. Limpe caches: "Administração do site" → "Desenvolvimento" → "Purgar todos os caches"
3. Inspecione o CSS gerado no navegador

### Logs de Erro
- Erros de compilação SCSS aparecem no log de erros do Moodle
- Sintaxe SCSS inválida impede a compilação completa

### Ferramentas de Debug
```php
// Em lib.php, adicione para debug:
error_log('Pre SCSS: ' . $scss);
```

## Boas Práticas

1. **Sempre use variáveis** para cores e valores reutilizáveis
2. **Organize mixins** logicamente em pre.scss
3. **Aplique estilos** em post.scss usando os mixins definidos
4. **Teste em múltiplos navegadores** após mudanças
5. **Mantenha especificidade CSS baixa** para facilitar overrides

## Conclusão

O sistema de compilação SCSS do ShiftClass está totalmente configurado e otimizado para:
- ✅ Carregar e compilar pre.scss e post.scss corretamente
- ✅ Permitir customização através de variáveis CSS
- ✅ Preparar a estrutura para perfis visuais dinâmicos
- ✅ Manter compatibilidade com o tema Boost
- ✅ Garantir performance através de cache eficiente