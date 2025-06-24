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
$string['brandcolor'] = 'Cor da marca';
$string['brandcolor_desc'] = 'A cor principal usada em todo o tema.';

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
$string['secondarycolor'] = 'Cor secundária';
$string['secondarycolor_help'] = 'Cor usada para botões, links e destaques.';
$string['backgroundcolor'] = 'Cor de fundo';
$string['backgroundcolor_help'] = 'Cor usada para o fundo da página.';

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

// Gerenciamento de perfis
$string['createnewprofile'] = 'Criar novo perfil';
$string['editprofile'] = 'Editar perfil';
$string['deleteprofile'] = 'Excluir perfil';
$string['editingprofile'] = 'Editando perfil: {$a}';
$string['existingprofiles'] = 'Perfis existentes';
$string['installdefaultprofiles'] = 'Instalar perfis padrão';
$string['defaultprofilesinstalled'] = 'Perfis padrão instalados com sucesso';
$string['colors'] = 'Cores';
$string['usage'] = 'Uso';
$string['lastmodified'] = 'Última modificação';
$string['actions'] = 'Ações';
$string['preview'] = 'Visualizar';
$string['usedinxcourses'] = 'Usado em {$a} curso(s)';
$string['notused'] = 'Não utilizado';
$string['statistics'] = 'Estatísticas';
$string['totalprofiles'] = 'Total de perfis';
$string['totalcoursesusingprofiles'] = 'Cursos usando perfis';
$string['profilecreated'] = 'Perfil criado com sucesso';
$string['profileupdated'] = 'Perfil atualizado com sucesso';
$string['profiledeleted'] = 'Perfil excluído com sucesso';
$string['deleteprofileconfirm'] = 'Tem certeza de que deseja excluir o perfil "{$a}"?';
$string['profiledetails'] = 'Detalhes do perfil';
$string['created'] = 'Criado';
$string['backtolist'] = 'Voltar à lista';
$string['profileforminfo'] = 'Crie um perfil visual com cores personalizadas. A visualização será atualizada conforme você faz alterações.';
$string['profileusedincourses'] = 'Este perfil está sendo usado por {$a} curso(s) e não pode ser excluído.';
$string['cannotdelete_inuse'] = 'Não é possível excluir - perfil em uso';
$string['profilepreview'] = 'Visualização do Perfil';
$string['noprofilescreated'] = 'Nenhum perfil visual foi criado ainda. Clique em "Criar novo perfil" para começar.';

// Formulário de perfil
$string['defaultheaderimage'] = 'Imagem de cabeçalho padrão';
$string['defaultheaderimage_help'] = 'Envie uma imagem de cabeçalho padrão para cursos que usam este perfil.';
$string['samplenavbar'] = 'Navegação do Curso';
$string['samplecontent'] = 'Conteúdo do Curso';
$string['sampletext'] = 'Assim ficará seu curso com este perfil visual aplicado.';
$string['primarybutton'] = 'Ação Primária';
$string['secondarybutton'] = 'Ação Secundária';
$string['contrastvalidation'] = 'Verificando contraste de cores para acessibilidade (WCAG 2.1)';
$string['contrastpass'] = 'Contraste excelente! Atende aos padrões WCAG AA.';
$string['contrastwarning'] = 'O contraste poderia ser melhor. Atende WCAG AA apenas para texto grande.';
$string['contrastfail'] = 'Contraste ruim. Considere usar cores diferentes para melhor acessibilidade.';

// Perfis padrão
$string['profile:corporate_blue'] = 'Azul Corporativo';
$string['profile:nature_green'] = 'Verde Natureza';
$string['profile:modern_purple'] = 'Roxo Moderno';
$string['profile:dynamic_orange'] = 'Laranja Dinâmico';

// Mensagens de erro
$string['error:profilenotfound'] = 'Perfil visual não encontrado';
$string['error:invalidcolor'] = 'Formato de cor inválido. Use o formato hexadecimal (#RRGGBB)';
$string['error:duplicateprofilename'] = 'Já existe um perfil com este nome';
$string['error:profilenamerequired'] = 'O nome do perfil é obrigatório';
$string['error:profilenametoolong'] = 'O nome do perfil não deve exceder 50 caracteres';
$string['error:primarycolorrequired'] = 'A cor primária é obrigatória';
$string['error:secondarycolorrequired'] = 'A cor secundária é obrigatória';
$string['error:backgroundcolorrequired'] = 'A cor de fundo é obrigatória';
$string['error:profileinuse'] = 'Este perfil está em uso e não pode ser excluído';

// Eventos
$string['event:profilecreated'] = 'Perfil visual criado';
$string['event:profileupdated'] = 'Perfil visual atualizado';
$string['event:profiledeleted'] = 'Perfil visual excluído';

// Privacidade
$string['privacy:metadata:profiles'] = 'Informações sobre perfis visuais criados ou modificados por usuários';
$string['privacy:metadata:profiles:usermodified'] = 'O ID do usuário que criou ou modificou o perfil pela última vez';
$string['privacy:metadata:profiles:timecreated'] = 'O horário em que o perfil foi criado';
$string['privacy:metadata:profiles:timemodified'] = 'O horário em que o perfil foi modificado pela última vez';
$string['privacy:metadata:courseprofiles'] = 'Informações sobre perfis visuais atribuídos a cursos';
$string['privacy:metadata:courseprofiles:usermodified'] = 'O ID do usuário que atribuiu o perfil ao curso';
$string['privacy:metadata:courseprofiles:timecreated'] = 'O horário em que o perfil foi atribuído';
$string['privacy:metadata:courseprofiles:timemodified'] = 'O horário em que a atribuição foi modificada pela última vez';
$string['privacy:metadata:preference:highcontrast'] = 'Preferência do usuário para modo de alto contraste';
$string['privacy:metadata:preference:reducedmotion'] = 'Preferência do usuário para movimento reduzido';