const functions = require('firebase-functions');
const admin = require('firebase-admin');
const https = require('https');
https.post = require('https-post');

admin.initializeApp(functions.config().firebase);
let db = admin.firestore();
let referenceUsers = db.collection('Users');
let referenceConfig = db.collection('Config');

let patternPatty = '';
let accessToken = '';
let urlUserSlack = '';
let urlMessageSlack = '';
let tokenUser = '';
let tokenChatEphimeral = '';
let idChannelGeneral = '';
let urlMessageChatEphimeral = '';

//Config
exports.updateInfo = functions.https.onRequest((req, res) => {
    db.collection('Config').doc('Firebase').set({});
    db.collection('Config').doc('Slack').set({
        pattyPattern: ':dumpling:',
        accessToken: 'tokenAccesApiSlack',
        urlUserSlack: 'slack.com/api/users.info',
        urlMessageSlack: 'URLMessageSlack',
        tokenUsers: 'TokenUserSlack',
        idChannelGeneral: 'idChannel',
        tokenChatEphimeral: 'TokenApiSlack',
        urlMessageChatEphimeral: 'https://slack.com/api/chat.postEphemeral'
    });
});

exports.sumarYConfigurar = functions.https.onRequest((req, res) => {
    referenceConfig.doc('Slack').get().then(doc => {
        patternPatty = doc.data().pattyPattern;
        accessToken = doc.data().accessToken;
        urlUserSlack = doc.data().urlUserSlack;
        urlMessageSlack = doc.data().urlMessageSlack;
        tokenUser = doc.data().tokenUsers;
        idChannelGeneral = doc.data().idChannelGeneral;
        tokenChatEphimeral = doc.data().tokenChatEphimeral;
        urlMessageChatEphimeral = doc.data().urlMessageChatEphimeral;
        sumarEmpanadas(req, res);
        return doc;
    }).catch(err => {
        return err;
    });
    res.status(200).send('{"status":"ok"}');
});

exports.listarUsuarios = functions.https.onRequest((req, res) => {
    referenceUsers.get().then((snapshot) => {
        let data = [];
        snapshot.forEach((doc) => {
            data.push(doc.data());
        });
        data.sort(function (a, b) {
            return b.Patties - a.Patties;
        });
        let index = 1;
        let list = '';
        data.forEach(function (element) {
            if (index >= 2) list = list.concat(",");
            list = list.concat('{"text":"', index++, ') ', element.Name, ' *', element.Patties, '*"}');
        });
        let info = '{"response_type": "in_channel","text": "*Dashboard INP Empanadas*","attachments": [' + list + ']}';
        res.status(200).send(JSON.parse(info));
        return null;
    }).catch((err) => {
        console.log('Error getting documents', err);
        res.status(200).send('Error : ', err);
    });
});

function messageProcces(body, emisor) {
    let users = new Set();
    let text = body.event.text.trim();
    let kmp = KMP(patternPatty, text);
    let textPatties = kmp.length;
    kmp = KMP("<@", text);
    let kmp2 = KMP(">", text);
    emisor = emisor.data();
    let date = new Date();
    for (let i = 0; i < kmp.length; i++)
        if (body.event.user !== text.substring(kmp[i] + 2, kmp2[i]))
            users.add(text.substring(kmp[i] + 2, kmp2[i]));

    if (users.size > 0) {
        if ((emisor.DatePattiesRemaining.getDate() < date.getDate() && emisor.DatePattiesRemaining.getMonth() === date.getMonth()) || (emisor.DatePattiesRemaining.getMonth() < date.getMonth())) {
            emisor.DatePattiesRemaining = date;
            emisor.RemainingDailyPatties = 5;
        }
        if ((kmp.length * textPatties) > emisor.RemainingDailyPatties)
            getMenssageChat("No puedes enviar tantas empanadas. Solo te quedan *" + emisor.RemainingDailyPatties + "* Empanadas", body.event.user);
        else {
            let remaining = emisor.RemainingDailyPatties - (users.size * textPatties);
            emisor.RemainingDailyPatties = remaining;
            referenceUsers.doc(body.event.user).set(emisor, {merge: true});
            getMenssageChat("Te quedan *" + remaining + "* empanadas", body.event.user);
            users.forEach(function (item, sameItem, s) {
                if (body.event.user !== item) {
                    referenceUsers.doc(item).get().then(doc => {
                        if (doc.exists)
                            addPatties(item, textPatties);
                        else
                            registerUser(item, textPatties);
                        return null;
                    }).catch(err => {
                        console.log('Error : ', err);
                        return null;
                    });
                }
            });
        }
    }
}

function sumarEmpanadas(req, res) {
    let body = req.body;
    if (body.token === accessToken) {
        referenceUsers.doc(body.event.user).get().then(emisor => {
            if (!emisor.exists)
                registerUser(body.event.user, 0).then(data => {

                    messageProcces(body, data);
                    return data;
                }).catch(err => {
                    return err;
                });
            else
                messageProcces(body, emisor);
            return null;
        }).catch(err => {
            return err;
        })
    }
}

function getUserById(nameUser, cb) {
    https.get('https://' + urlUserSlack + '?token=' + tokenUser + '&user=' + nameUser, (resp) => {
        let data = '';
        resp.on('data', (chunk) => {
            data += chunk;
        });
        resp.on('end', () => {
            let result = JSON.parse(data);
            cb(null, result);
        });
    }).on("error", cb);
}

function registerUser(user, patties) {
    return getUserById(user, function (err, result) {
        if (err)
            console.log('Result slack user error : ' + err);
        console.log('Result slack user success : ' + result);
        referenceUsers.doc(user).set({
            Name: result.user.real_name,
            Patties: patties,
            Date: new Date(),
            DatePattiesRemaining: new Date(),
            RemainingDailyPatties: 5
        })
    });
}

function addPatties(user, patties) {
    referenceUsers.doc(user).get().then(doc => {
        let currentPatties = doc.data().Patties;
        let pattiesAdded = patties + currentPatties;
        referenceUsers.doc(user).set({
            Patties: pattiesAdded,
            Date: new Date()
        }, {merge: true});
        return null;
    }).catch(err => {
        console.log(err);
        return null;
    });
}

function getMenssageChat(text, idUser) {
    var request = require('request');
    var webhook = urlMessageChatEphimeral;
    var payload = {channel: idChannelGeneral, user: idUser, text: text};
    var header = {Authorization: "Bearer " + tokenChatEphimeral};
    request.post({url: webhook, json: payload, headers: header}, function (err, res) {
        if (err)
            console.log("Error:" + err);
        if (res)
            console.log(res.body);
    });
}


function KMP(wordQuery, string) {
    let T = getBorderArray(string);
    let sol = [];
    if (wordQuery.length === 0) return sol;
    for (let m = 0, i = 0; m + i < string.length;) {
        if (wordQuery.charAt(i) === string.charAt(m + i))
            if (++i === wordQuery.length)
                sol.push(m);
            else {
                m += i - T[i];
                if (i > 0) i = T[i];
            }
    }
    return sol;
}

function getBorderArray(word) {
    let T = [];
    T[0] = -1;
    T[1] = 0;
    for (let i = 2, j = 0; i <= word.length;) {
        if (word.charAt(i - 1) === word.charAt(j))
            T[i++] = ++j;
        else if (j > 0)
            j = T[j];
        else
            T[i++] = 0;
    }
    return T;
}
