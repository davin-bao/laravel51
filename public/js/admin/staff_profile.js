
var page = {
    urlParamId: $('#user-id').val(),
    hasLoaded: false,

    saveFormDom: $("#save-form"),
    idDom: $('#user-id'),
    nameDom: $('#name'),
    emailDom: $('#email'),
    mobileDom: $('#mobile'),

    sel2Dom: $('#sel2'),
    select2ChosenDom: $('.select2-chosen'),

    setAvatarContainerDom: $('#set-avatar-container'),
    toolBarDom: $('.tool-bar'),

    updateAvatarDom: $('#update-avatar'),

    init: function(data){
        this.initDom(data), this.initValidator(), this.addEvent();
    },
    initDom: function(data){
        var self = this;
        self.idDom.val(data.id);
        self.nameDom.val(data.name);
        self.emailDom.val(data.email);
        self.mobileDom.val(data.mobile);

        //添加设置头像
        self.setAvatarContainerDom.html(
            Widgets.OperateButtons._button(self, 'set-avatar', 'admin/static/upload-avatar', '头像设置', function () {}, 'btn-default')
        );

        //添加操作按钮
        self.toolBarDom.html(
            Widgets.OperateButtons.save(self, 'save', 'admin/edit', '保存', function(){
                window.location = Public.ROOT_URL + 'admin/profile';
            }) +
            Widgets.OperateButtons.back(self)
        );

        var select2 = new Selector.select2(self.sel2Dom, {dataType: 'clientSide', data: self.getTimezoneArray()});
        select2.val(data.timezone);
    },
    getTimezoneArray: function(){
        return {
            '亚洲': [
                'Asia/Shanghai', 'Asia/Aden', 'Asia/Almaty', 'Asia/Amman', 'Asia/Anadyr',
                'Asia/Aqtau', 'Asia/Aqtobe', 'Asia/Ashgabat', 'Asia/Baghdad',
                'Asia/Bahrain', 'Asia/Baku', 'Asia/Bangkok', 'Asia/Barnaul',
                'Asia/Beirut', 'Asia/Bishkek', 'Asia/Brunei', 'Asia/Chita',
                'Asia/Choibalsan', 'Asia/Colombo', 'Asia/Damascus', 'Asia/Dhaka',
                'Asia/Dili', 'Asia/Dubai', 'Asia/Dushanbe', 'Asia/Gaza',
                'Asia/Hebron', 'Asia/Ho_Chi_Minh', 'Asia/Hong_Kong', 'Asia/Hovd',
                'Asia/Irkutsk', 'Asia/Jakarta', 'Asia/Jayapura', 'Asia/Jerusalem',
                'Asia/Kabul', 'Asia/Kamchatka', 'Asia/Karachi', 'Asia/Kathmandu',
                'Asia/Khandyga', 'Asia/Kolkata', 'Asia/Krasnoyarsk', 'Asia/Kuala_Lumpur',
                'Asia/Kuching', 'Asia/Kuwait', 'Asia/Macau', 'Asia/Magadan',
                'Asia/Makassar', 'Asia/Manila', 'Asia/Muscat', 'Asia/Nicosia',
                'Asia/Novokuznetsk', 'Asia/Novosibirsk', 'Asia/Omsk', 'Asia/Oral',
                'Asia/Phnom_Penh', 'Asia/Pontianak', 'Asia/Pyongyang', 'Asia/Qatar',
                'Asia/Qyzylorda', 'Asia/Rangoon', 'Asia/Riyadh', 'Asia/Sakhalin',
                'Asia/Samarkand', 'Asia/Seoul', 'Asia/Singapore',
                'Asia/Srednekolymsk', 'Asia/Taipei', 'Asia/Tashkent', 'Asia/Tbilisi',
                'Asia/Tehran', 'Asia/Thimphu', 'Asia/Tokyo', 'Asia/Tomsk',
                'Asia/Ulaanbaatar', 'Asia/Urumqi', 'Asia/Ust-Nera', 'Asia/Vientiane',
                'Asia/Vladivostok', 'Asia/Yakutsk', 'Asia/Yekaterinburg', 'Asia/Yerevan'
            ],'欧洲': [
                'Europe/Amsterdam', 'Europe/Andorra', 'Europe/Astrakhan', 'Europe/Athens',
                'Europe/Belgrade', 'Europe/Berlin', 'Europe/Bratislava', 'Europe/Brussels',
                'Europe/Bucharest', 'Europe/Budapest', 'Europe/Busingen', 'Europe/Chisinau',
                'Europe/Copenhagen', 'Europe/Dublin', 'Europe/Gibraltar', 'Europe/Guernsey',
                'Europe/Helsinki', 'Europe/Isle_of_Man', 'Europe/Istanbul', 'Europe/Jersey',
                'Europe/Kaliningrad', 'Europe/Kiev', 'Europe/Kirov', 'Europe/Lisbon',
                'Europe/Ljubljana', 'Europe/London', 'Europe/Luxembourg', 'Europe/Madrid',
                'Europe/Malta', 'Europe/Mariehamn', 'Europe/Minsk', 'Europe/Monaco',
                'Europe/Moscow', 'Europe/Oslo', 'Europe/Paris', 'Europe/Podgorica',
                'Europe/Prague', 'Europe/Riga', 'Europe/Rome', 'Europe/Samara',
                'Europe/San_Marino', 'Europe/Sarajevo', 'Europe/Simferopol', 'Europe/Skopje',
                'Europe/Sofia', 'Europe/Stockholm', 'Europe/Tallinn', 'Europe/Tirane',
                'Europe/Ulyanovsk', 'Europe/Uzhgorod', 'Europe/Vaduz', 'Europe/Vatican',
                'Europe/Vienna', 'Europe/Vilnius', 'Europe/Volgograd', 'Europe/Warsaw',
                'Europe/Zagreb', 'Europe/Zaporozhye', 'Europe/Zurich'
            ],'美洲': [
                'America/Adak','America/Anchorage','America/Anguilla','America/Antigua',
                'America/Araguaina','America/Argentina/Buenos_Aires','America/Argentina/Catamarca','America/Argentina/Cordoba',
                'America/Argentina/Jujuy','America/Argentina/La_Rioja','America/Argentina/Mendoza','America/Argentina/Rio_Gallegos',
                'America/Argentina/Salta','America/Argentina/San_Juan','America/Argentina/San_Luis','America/Argentina/Tucuman',
                'America/Argentina/Ushuaia','America/Aruba','America/Asuncion','America/Atikokan',
                'America/Bahia','America/Bahia_Banderas','America/Barbados','America/Belem',
                'America/Belize','America/Blanc-Sablon','America/Boa_Vista','America/Bogota',
                'America/Boise','America/Cambridge_Bay','America/Campo_Grande','America/Cancun',
                'America/Caracas','America/Cayenne','America/Cayman','America/Chicago',
                'America/Chihuahua','America/Costa_Rica','America/Creston','America/Cuiaba',
                'America/Curacao','America/Danmarkshavn','America/Dawson','America/Dawson_Creek',
                'America/Denver','America/Detroit','America/Dominica','America/Edmonton',
                'America/Eirunepe','America/El_Salvador','America/Fort_Nelson','America/Fortaleza',
                'America/Glace_Bay','America/Godthab','America/Goose_Bay','America/Grand_Turk',
                'America/Grenada','America/Guadeloupe','America/Guatemala','America/Guayaquil',
                'America/Guyana','America/Halifax','America/Havana','America/Hermosillo',
                'America/Indiana/Indianapolis','America/Indiana/Knox','America/Indiana/Marengo','America/Indiana/Petersburg',
                'America/Indiana/Tell_City','America/Indiana/Vevay','America/Indiana/Vincennes','America/Indiana/Winamac',
                'America/Inuvik','America/Iqaluit','America/Jamaica','America/Juneau',
                'America/Kentucky/Louisville','America/Kentucky/Monticello','America/Kralendijk','America/La_Paz',
                'America/Lima','America/Los_Angeles','America/Lower_Princes','America/Maceio',
                'America/Managua','America/Manaus','America/Marigot','America/Martinique',
                'America/Matamoros','America/Mazatlan','America/Menominee','America/Merida',
                'America/Metlakatla','America/Mexico_City','America/Miquelon','America/Moncton',
                'America/Monterrey','America/Montevideo','America/Montserrat','America/Nassau',
                'America/New_York','America/Nipigon','America/Nome','America/Noronha',
                'America/North_Dakota/Beulah','America/North_Dakota/Center','America/North_Dakota/New_Salem','America/Ojinaga',
                'America/Panama','America/Pangnirtung','America/Paramaribo','America/Phoenix',
                'America/Port-au-Prince','America/Port_of_Spain','America/Porto_Velho','America/Puerto_Rico',
                'America/Rainy_River','America/Rankin_Inlet','America/Recife','America/Regina',
                'America/Resolute','America/Rio_Branco','America/Santarem','America/Santiago',
                'America/Santo_Domingo','America/Sao_Paulo','America/Scoresbysund','America/Sitka',
                'America/St_Barthelemy','America/St_Johns','America/St_Kitts','America/St_Lucia',
                'America/St_Thomas','America/St_Vincent','America/Swift_Current','America/Tegucigalpa',
                'America/Thule','America/Thunder_Bay','America/Tijuana','America/Toronto',
                'America/Tortola','America/Vancouver','America/Whitehorse','America/Winnipeg',
                'America/Yakutat','America/Yellowknife'
            ],'非洲': [
                'Africa/Abidjan', 'Africa/Accra', 'Africa/Addis_Ababa', 'Africa/Algiers',
                'Africa/Asmara', 'Africa/Bamako', 'Africa/Bangui', 'Africa/Banjul',
                'Africa/Bissau', 'Africa/Blantyre', 'Africa/Brazzaville', 'Africa/Bujumbura',
                'Africa/Cairo', 'Africa/Casablanca', 'Africa/Ceuta', 'Africa/Conakry',
                'Africa/Dakar', 'Africa/Dar_es_Salaam', 'Africa/Djibouti', 'Africa/Douala',
                'Africa/El_Aaiun', 'Africa/Freetown', 'Africa/Gaborone', 'Africa/Harare',
                'Africa/Johannesburg', 'Africa/Juba', 'Africa/Kampala', 'Africa/Khartoum',
                'Africa/Kigali', 'Africa/Kinshasa', 'Africa/Lagos', 'Africa/Libreville',
                'Africa/Lome', 'Africa/Luanda', 'Africa/Lubumbashi', 'Africa/Lusaka',
                'Africa/Malabo', 'Africa/Maputo', 'Africa/Maseru', 'Africa/Mbabane',
                'Africa/Mogadishu', 'Africa/Monrovia', 'Africa/Nairobi', 'Africa/Ndjamena',
                'Africa/Niamey', 'Africa/Nouakchott', 'Africa/Ouagadougou', 'Africa/Porto-Novo',
                'Africa/Sao_Tome', 'Africa/Tripoli', 'Africa/Tunis', 'Africa/Windhoek'
            ],'澳洲': [
                'Australia/Adelaide', 'Australia/Brisbane', 'Australia/Broken_Hill', 'Australia/Currie',
                'Australia/Darwin', 'Australia/Eucla', 'Australia/Hobart', 'Australia/Lindeman',
                'Australia/Lord_Howe', 'Australia/Melbourne', 'Australia/Perth', 'Australia/Sydney'
            ],'太平洋地区': [
                'Pacific/Apia', 'Pacific/Auckland', 'Pacific/Bougainville', 'Pacific/Chatham',
                'Pacific/Chuuk', 'Pacific/Easter', 'Pacific/Efate', 'Pacific/Enderbury',
                'Pacific/Fakaofo', 'Pacific/Fiji', 'Pacific/Funafuti', 'Pacific/Galapagos',
                'Pacific/Gambier', 'Pacific/Guadalcanal', 'Pacific/Guam', 'Pacific/Honolulu',
                'Pacific/Johnston', 'Pacific/Kiritimati', 'Pacific/Kosrae', 'Pacific/Kwajalein',
                'Pacific/Majuro', 'Pacific/Marquesas', 'Pacific/Midway', 'Pacific/Nauru',
                'Pacific/Niue', 'Pacific/Norfolk', 'Pacific/Noumea', 'Pacific/Pago_Pago',
                'Pacific/Palau', 'Pacific/Pitcairn', 'Pacific/Pohnpei', 'Pacific/Port_Moresby',
                'Pacific/Rarotonga', 'Pacific/Saipan', 'Pacific/Tahiti', 'Pacific/Tarawa',
                'Pacific/Tongatapu', 'Pacific/Wake', 'Pacific/Wallis'
            ],'印度': [
                'Indian/Antananarivo', 'Indian/Chagos', 'Indian/Christmas', 'Indian/Cocos',
                'Indian/Comoro', 'Indian/Kerguelen', 'Indian/Mahe', 'Indian/Maldives',
                'Indian/Mauritius', 'Indian/Mayotte', 'Indian/Reunion'
            ],'大西洋': [
                'Atlantic/Azores', 'Atlantic/Bermuda', 'Atlantic/Canary', 'Atlantic/Cape_Verde',
                'Atlantic/Faroe', 'Atlantic/Madeira', 'Atlantic/Reykjavik', 'Atlantic/South_Georgia',
                'Atlantic/St_Helena', 'Atlantic/Stanley'
            ],'南极洲': [
                'Antarctica/Casey', 'Antarctica/Davis', 'Antarctica/DumontDUrville', 'Antarctica/Macquarie',
                'Antarctica/Mawson', 'Antarctica/McMurdo', 'Antarctica/Palmer', 'Antarctica/Rothera',
                'Antarctica/Syowa', 'Antarctica/Troll', 'Antarctica/Vostok'
            ],'北极洲': [
                'Arctic/Longyearbyen'
            ]
        };
    },
    initValidator: function(){
        var self = this;

        self.mobileDom.mask("(999) 999-9999? x9999");

        self.saveFormDom.validate({
            rules: {
                email: {
                    required: ["邮箱"],
                    email: [],
                    rangelength: [0, 30]
                },
                name: {
                    required: ["用户名"],
                    rangelength: [0, 30]
                },
                mobile: {
                    mobileExt: ["手机号"],
                    rangelength: [7, 20]
                },
            },
            messages: {
                mobile: {
                    number:'请输入正确手机号！',
                },
            },
            errorClass: "has-error"
        });
    },
    addEvent: function(){
        var self = this;

        // 为上传头像配置点击事件
        self.updateAvatarDom.on('click', function () {

            var header = '无需保存即可设置';
            var title = '头像设置';
            var info = [
                '一拖到此处就可更换你的头像',
                '图片大小需小于1M'
            ];

            Widgets.Dialogs.uploadImage(header, title, info, function() {}, '完成');
        });

        // 为设置头像按钮配置点击事件
        self.setAvatarContainerDom.on('click', '#set-avatar', function () {
            self.updateAvatarDom.click();
        });
    },
    getPostData: function(){
        var self = this;
        return {
            id: self.idDom.val(),
            name: self.nameDom.val(),
            email: self.emailDom.val(),
            mobile: self.mobileDom.val(),
            timezone: self.sel2Dom.val()
        };
    },
    getOrigData: function(){
        return {
            id: 0,
            name: '',
            emai: '',
            mobile: '(086) 138-1234 x5678',
            timezone: 'Asia/Shanghai'
        };
    },
};

$(function() {
    var data = page.getOrigData();
    if (page.urlParamId != -1) {
        if (!page.hasLoaded) {
            Public.ajaxGet("admin/staff/edit/", {'id': page.urlParamId}, function(result) {
                200 === result.code ? (data = result.data, page.init(data), page.hasLoaded = !0) : (Widgets.tips({
                    type: 'error',
                    message: result.msg
                }))
            });
        }
    } else page.init(data);
});