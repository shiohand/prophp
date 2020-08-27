function addEventForMemberCreate() {
  document.getElementById('name').addEventListener(onchange, (e) => { /* 処理 */ })
}

function signupInputsToggle(target) {
  if (false) {
    inputIds = ['inputたち', '', ''];
    inputIds.forEach(inputId => {
      document.getElementById(inputId);
    }); // 配列アウトプットするやつ忘れた。
    signupInputs.map((input) => {input.disabled = true});
  }
}