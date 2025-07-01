<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language file for theme_shiftclass (Portuguese - Brazil)
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Nome e descrição do plugin
$string['pluginname'] = 'ShiftClass';
$string['choosereadme'] = 'ShiftClass - Educar é se adaptar. E o seu tema sabe disso. Um tema moderno e personalizável com perfis visuais para cursos.';

// Configurações
$string['configtitle'] = 'ShiftClass';
$string['generalsettings'] = 'Configurações gerais';
$string['advancedsettings'] = 'Configurações avançadas';

// Configurações gerais
$string['preset'] = 'Predefinição do tema';
$string['preset_desc'] = 'Escolha uma predefinição para alterar amplamente a aparência do tema.';
$string['presetdefault'] = 'Padrão';
$string['presetplain'] = 'Simples';

// Seção de cores
$string['colorsheading'] = 'Configurações de Cores';
$string['colorsheading_desc'] = 'Personalize as cores do tema. Essas cores serão usadas em todo o site e podem ser sobrescritas por perfis visuais individuais de cursos.';

$string['brandcolor'] = 'Cor primária';
$string['brandcolor_desc'] = 'A cor primária usada para navegação, botões e elementos principais em todo o tema.';

$string['secondarycolor'] = 'Cor secundária';
$string['secondarycolor_desc'] = 'A cor secundária usada para elementos de apoio, estados de hover e recursos de design complementares.';

$string['accentcolor'] = 'Cor de destaque';
$string['accentcolor_desc'] = 'A cor de destaque usada para realces, mensagens de sucesso e elementos de chamada para ação.';

$string['backgroundcolor'] = 'Cor de fundo';
$string['backgroundcolor_desc'] = 'A cor de fundo principal das páginas. Escolha uma cor clara para melhor legibilidade.';

// Configurações avançadas
$string['rawscsspre'] = 'SCSS inicial bruto';
$string['rawscsspre_desc'] = 'Neste campo você pode fornecer código SCSS de inicialização, ele será injetado antes de todo o resto. Na maioria das vezes você usará esta configuração para definir variáveis.';
$string['rawscss'] = 'SCSS bruto';
$string['rawscss_desc'] = 'Use este campo para fornecer código SCSS ou CSS que será injetado no final da folha de estilos.';

// Perfis Visuais
$string['visualprofiles'] = 'Perfis Visuais';
$string['visualprofilesinfo'] = 'Sobre Perfis Visuais';
$string['visualprofilesinfo_desc'] = 'Perfis visuais permitem criar esquemas de cores personalizados que podem ser aplicados a cursos individuais. Cada perfil consiste em um nome e três cores que definem a aparência do curso.';
$string['manageprofiles'] = 'Gerenciar Perfis Visuais';
$string['manageprofiles_desc'] = 'O gerenciamento de perfis visuais estará disponível na próxima fase de desenvolvimento.';

// Strings relacionadas a perfis (para fases futuras)
$string['profilename'] = 'Nome do perfil';
$string['profilename_help'] = 'Digite um nome único para este perfil visual.';
$string['primarycolor'] = 'Cor primária';
$string['primarycolor_help'] = 'Cor principal usada para barra de navegação e elementos principais.';
$string['secondarycolor_profile'] = 'Cor secundária';
$string['secondarycolor_profile_help'] = 'Cor usada para botões, links e destaques.';
$string['backgroundcolor_profile'] = 'Cor de fundo';
$string['backgroundcolor_profile_help'] = 'Cor usada para o fundo da página.';

// Configurações do curso (para fases futuras)
$string['coursevisualprofile'] = 'Perfil visual';
$string['coursevisualprofile_help'] = 'Selecione um perfil visual para personalizar a aparência deste curso.';
$string['novisualprofile'] = 'Sem perfil visual';

// Capacidades
$string['shiftclass:manageprofiles'] = 'Gerenciar perfis visuais';

// Privacidade
$string['privacy:metadata'] = 'O tema ShiftClass não armazena nenhum dado pessoal.';

// Acessibilidade
$string['region-side-pre'] = 'Barra lateral esquerda';

// Mensagens de erro
$string['error:profilenotfound'] = 'Perfil visual não encontrado';
$string['error:invalidcolor'] = 'Formato de cor inválido. Use o formato hexadecimal (#RRGGBB)';
$string['error:duplicateprofilename'] = 'Já existe um perfil com este nome';

// Visualização de cores e auxiliares
$string['colorpreview'] = 'Visualização de cores';
$string['resetcolors'] = 'Redefinir para cores padrão';
$string['colorscheme'] = 'Esquema de cores';
$string['customcolors'] = 'Cores personalizadas';
$string['colortips'] = 'Dicas de cores';
$string['colortips_desc'] = 'Escolha cores que forneçam bom contraste para legibilidade. A cor primária deve ser forte o suficiente para elementos de navegação, enquanto o fundo deve ser claro e sutil.';