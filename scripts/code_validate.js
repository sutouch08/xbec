function validCode(input){
  var regex = /[^a-z0-9-_]+/gi;
  input.value = input.value.replace(regex, '');
}
