/**
 * Меняет активность кнопок "Пересчитать" и 
 * "Оформить заказ" в корзине пользователя
 */
function result(){
   var change = document.getElementById('change');
   change.removeAttribute("disabled");
   change.setAttribute("class", "button");
   var submit = document.getElementById('submit');
   submit.removeAttribute("class");
   submit.setAttribute("disabled", "disabled");
}




/** 
 * Выводит сообщения об ошибках при заполнении формы
 */ 
function changeForm (form) {

   function helpText (id, idHelp, helpText) {

      if (helpText == "") {
          var color = "";
         }else{
            var color = "red";
         }
      document.getElementById(id).style.borderColor = color;
      document.getElementById(idHelp).innerHTML = helpText;
   }
   
   var name = document.getElementById("name").value;
   var phone = document.getElementById("phone").value;

   
   if (name != ''){
      var test1 = true;
      helpText ("name", "help1", "");
   }else {
      var test1 = false;
      helpText ("name", "help1", 'Пожалуйста введите Ваше имя!');
   };
         
   
   
   var regPhone = /^(\+\d{2})?(-|\s)?\d{3}(-|\s)?\d{3}(-|\s)?\d{2,4}(-|\s)?\d{2,4}(\s)?$/;
   if (phone != '' && regPhone.test(phone)) {
      var test2 = true;
      helpText ("phone", "help2", "");
   }else{
      if(phone == ''){
      var test2 = false;
      helpText ("phone", "help2", "Пожалуйста укажите телефон!")
      }else{
         var test2 = false;
         helpText ("phone", "help2", "Пожалуйста укажите корректный контактный телефон!")
      }
   }  
      
   if (test1 && test2) {
      form.submit();
   }else {
      alert("Пожалуйста заполните все поля, согласно подсказкам, которые появляются справа от каждого заполняемого поля!");
   };    
};
      
