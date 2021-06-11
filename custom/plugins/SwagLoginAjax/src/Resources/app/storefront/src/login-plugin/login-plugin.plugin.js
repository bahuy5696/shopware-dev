import HttpClient from 'src/service/http-client.service';
import Plugin from 'src/plugin-system/plugin.class';

export default class LoginPlugin extends Plugin {
    init() {
        this.onLoginSubmitEvent()
    }

    onLoginSubmitEvent() {
        $('#loginSubmitBtn').click(function (e) {
            e.preventDefault();
            const token = $("input[name=_csrf_token]").val();
            const username = $('#loginMail').val();
            const password = $('#loginPassword').val();
            const data = {
                _csrf_token: token,
                username,
                password
            }
            const client = new HttpClient();
            client.post('/account/login',JSON.stringify(data), function (res) {
                console.log(res);
            }, 'application/json', true)

        });

    }

}
