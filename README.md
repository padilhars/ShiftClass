# ShiftClass Theme - ETAPA 1: Estrutura Base

## Visão Geral

Este documento descreve a implementação da **ETAPA 1** do tema ShiftClass para Moodle. Esta fase estabelece a estrutura fundamental do tema com herança completa do Boost e preparação para o sistema de perfis visuais.

## Estrutura de Arquivos Criada

```
theme/shiftclass/
├── version.php                    # Informações de versão e dependências
├── config.php                     # Configuração principal (herança, layouts, SCSS)
├── lib.php                        # Funções principais e processamento SCSS
├── settings.php                   # Configurações administrativas
├── lang/
│   ├── en/
│   │   └── theme_shiftclass.php   # Strings em inglês
│   └── pt_br/
│       └── theme_shiftclass.php   # Strings em português brasileiro
├── db/
│   ├── access.php                 # Definição de capacidades
│   └── install.xml                # Estrutura do banco de dados
├── classes/
│   └── output/
│       └── core_renderer.php      # Renderizador customizado
├── scss/
│   ├── pre.scss                   # Variáveis CSS e mixins
│   ├── post.scss                  # Estilos customizados
│   ├── core.scss                  # Estilos principais (placeholder)
│   └── custom.scss                # Importador de estilos
└── pix/
    └── screenshot.html            # HTML de demonstração para screenshot
```

## Funcionalidades Implementadas

### 1. Herança do Tema Boost
- Configuração completa em `config.php` com todos os layouts herdados
- Aproveitamento de toda a estrutura moderna do Boost
- Compatibilidade com Moodle 5.x

### 2. Sistema de Processamento SCSS
O processamento SCSS está configurado através de três callbacks principais:

- **`theme_shiftclass_get_main_scss_content()`**: Carrega o SCSS principal
- **`theme_shiftclass_get_pre_scss()`**: Processa `pre.scss` com variáveis e mixins
- **`theme_shiftclass_get_extra_scss()`**: Processa `post.scss` com estilos customizados

#### Fluxo de Compilação SCSS:
1. Variáveis do tema são injetadas primeiro
2. `pre.scss` é processado (variáveis CSS, mixins)
3. SCSS do Boost é incluído
4. `core.scss` e `custom.scss` são processados
5. `post.scss` é aplicado por último (estilos customizados)

### 3. Suporte Multilíngue
- Strings completas em inglês (`lang/en/theme_shiftclass.php`)
- Strings completas em português brasileiro (`lang/pt_br/theme_shiftclass.php`)
- Todas as strings preparadas para as próximas fases

### 4. Sistema de Perfis Visuais (Estrutura Base)
- Tabelas de banco de dados definidas:
  - `theme_shiftclass_profiles`: Armazena perfis visuais
  - `theme_shiftclass_course_profiles`: Associa cursos a perfis
- Capacidades definidas para gerenciamento de perfis
- Funções stub em `lib.php` para implementação futura

### 5. CSS Variables e Mixins
Em `pre.scss`:
- Variáveis CSS customizadas para cores dinâmicas
- Mixins para aplicação de perfis visuais
- Mixins de acessibilidade (alto contraste, movimento reduzido)
- Mixins responsivos

### 6. Renderizador Customizado
- Preparado para aplicar perfis visuais dinamicamente
- Suporte para imagem de cabeçalho do curso
- Melhorias de acessibilidade integradas

### 7. Configurações Administrativas
- Interface básica de configurações
- Aba preparada para gerenciamento de perfis visuais
- Configurações de SCSS customizado

## Instalação

1. Copie a pasta `shiftclass` para `moodle/theme/`
2. Acesse o Moodle como administrador
3. Vá para "Administração do site" → "Notificações"
4. O Moodle detectará o novo tema e executará a instalação
5. Após a instalação, vá para "Aparência" → "Seletor de temas"
6. Selecione "ShiftClass" como tema padrão

## Configuração

1. Vá para "Administração do site" → "Aparência" → "ShiftClass"
2. Configure as opções gerais:
   - Preset do tema (Default ou Plain)
   - Cor da marca
3. Opções avançadas permitem adicionar SCSS customizado

## Próximas Etapas

### ETAPA 2: Sistema de Perfis Visuais - Backend
- Implementar CRUD completo para perfis
- Criar interface de gerenciamento
- Adicionar perfis padrão
- Sistema de cache otimizado

### ETAPA 3: Integração com Cursos
- Adicionar seleção de perfil nas configurações do curso
- Aplicar perfis dinamicamente
- Implementar cabeçalho do curso com imagem

## Notas Técnicas

### Segurança
- Validação de dados preparada com `clean_param()`
- Capacidades definidas para controle de acesso
- Estrutura preparada para proteção CSRF

### Performance
- Callbacks SCSS otimizados
- Estrutura preparada para cache de perfis
- Carregamento eficiente de recursos

### Acessibilidade
- Mixins SCSS para alto contraste
- Suporte a preferência de movimento reduzido
- Estrutura HTML semântica

### Compatibilidade
- Moodle 5.0+
- PHP 7.4+
- Todos os navegadores modernos

## Screenshot

Para gerar o screenshot.jpg:
1. Abra `pix/screenshot.html` em um navegador
2. Redimensione a janela para 800x600px
3. Capture a tela e salve como `pix/screenshot.jpg`