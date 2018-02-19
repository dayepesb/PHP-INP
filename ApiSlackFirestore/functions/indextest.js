function getMenssageChat2() {
    var request = require('request');
    var webhook = "https://slack.com/api/chat.postEphemeral";
    var payload = {channel:"C96KUFCPL",user:"U96PCMLTF", text:"sadsd"};
    var header = {Authorization: "Bearer xoxb-317474325317-eKyvqsemr1IZpqLdaILxQtIe"}
    request.post({url: webhook, json: payload,headers:header}, function (err, res) {
        if (err)
            console.log("Error:" + err);
        if (res)
            console.log(res.body);
    });
}

getMenssageChat2();