$(document).ready(function(){
   const $originURL = window.location.origin; // console.log($originURL);
   // const $originHREF = window.location.href.replace('#', ''); // console.log($originURL);
   let $projects = []; // tableau qui contiendra toutes les checkboxes

   // constructeur objet Project
   function Project (id, name) {
      this.id = id;
      this.id = name;
   }

   // fonction de classement ASC des catégories cochées
   // => https://stackoverflow.com/questions/1129216/sort-array-of-objects-by-string-property-value
   function orderedASC( a, b ) {
      if ( a.name < b.name ) return -1;
      if ( a.name > b.name ) return 1;
      return 0;
   }

   // champs de recherche => https://api.jqueryui.com/autocomplete/
   $(function () {
      $('#search-project').autocomplete({
         delay: 1000,
         minLength: 3,
         source: $source,
         select: function (event, ui) {
            $('#search-project').val(ui.item.name);
            window.location.href = $originURL+ "/projects/project/" + ui.item.id;
            return false;
         }
      })
          .autocomplete("instance")._renderItem = function (ul, item) {
         return $("<li>")
             .append(item.name + ' / ' + item.info_short)
             .appendTo(ul);
      };
   });

   // bouton filtrer
   $('#filter').click(function(){
      let $param = null;
      if ($projects.length > 0) {
         $param = '';
         for (let i=0; i<$projects.length; i++) {
            if ($projects.length > 1 && i !== 0){
               $param += ('and' + $projects[i].id);
            } else {
               $param += $projects[i].id;
            }
         }
      }
      // console.log($projects);
      // console.log($param);
      // console.log($originURL + $param)
      window.location.href = $originURL + "/projects/?category=" + $param;
   });

   // boutons checkboxes et radios
   $('.form-check').each(function () {
      // const $input = $(this).find('input')['prevObject'][0]; // console.log($input);
      const $input = $(this).find('input'); // console.log($input);
      // const $index = $(this).find('input')[0]['id']; // console.log($index);
      const $type = $(this).find('input')[0]['type']; // console.log($type);
      const $checked = $(this).find('input')[0]['checked']; // console.log($checked);
      const $label = $(this).find('label')[0]['innerText']; // console.log($label);
      const $id = $(this).find('input')[0]['id'].replace(/^[a-zA-Z]+/, '');

      // tableau contenant toutes les checkboxes
      let project = {};
      // if ($checked && $type === 'checkbox') {
      //     $projects.push( new Project($id, $label) );
      // }

      // évènements pour les boutons checkboxes et radios
      $input.change(function (e){
         e.preventDefault();

         // boutons radios
         if ($input.is(":checked") && $input[0].getAttribute('type') === "radio") {
            window.location.href = $originURL + '/projects/?type=' + $id;
         }

         // boutons checkboxes
         if ($input.is(":checked") && $input[0].getAttribute('type') === "checkbox") {
            $projects.push( new Project($id, $input[0].offsetParent.innerText) );
            $projects.sort(orderedASC); // console.log($projects);
         } else if (!$input.is(":checked") && $input[0].getAttribute('type') === "checkbox") {
            $projects.splice($projects.indexOf(project[$id]), 1); // console.log($projects);
         }
         // console.log($projects);
      })
   });
   // console.log($projects);



});