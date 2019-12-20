"use strict";

class Tools
{
    constructor()
    {
        this.ERROR_CODE_UNDEFINED = {
            code: -99
        };

        this.DATE_LOCALE = 'de-DE';
        this.DATE_OPTIONS = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        };
    }

    /**
     * @param {String} _suffix
     * @return {String}
     */
    static getBaseUrl(_suffix)
    {
        let appLocation = location.href.replace('#', '');
        return appLocation + 'app.php/' + _suffix;
    }

    /**
     * @param {Object} _response
     * @property {Number} code
     * @property {Object} config
     * @property {Object} list
     */
    static showErrorByCode(_response)
    {
        let message = '';
        switch (_response.code) {
            case 0: // all right
                break;
            case -1:
                message = '<b>API ERROR</b><br />Missing required arguments';
                break;
            case -2:
                message = '<b>API ERROR</b><br />Can not insert same day twice';
                break;
            case -3:
                message = '<b>API ERROR</b><br />No entries found';
                break;
            case -4:
                message = '<b>API ERROR</b><br />Can not recalculate working time';
                break;
            case -5:
                message = '<b>API ERROR</b><br />Can not update config data';
                break;
            case -99:
                message = '<b>HTTP ERROR</b><br />API not found';
                break;
            default:
                message = '<b>ERROR</b><br />Unknown Error';
        }

        let modal = $('#modal-message');
            modal.find('.modal-header h4').html('¯\\_(ツ)_/¯');
            modal.find('.modal-body').html(message);
            modal.modal('show');
    }

    static showRandomQuote()
    {
        let quotes = [
            'Man sollte nie so viel zu tun haben, dass man zum Nachdenken keine Zeit mehr hat. <cite title="Source Title">By Georg Christoph Lichtenberg</cite>',
            'Es gibt eine Zeit für die Arbeit. Und es gibt eine Zeit für die Liebe. Mehr Zeit hat man nicht. <cite title="Source Title">By Coco Chanel</cite>',
            'Die Leute, die niemals Zeit haben, tun am wenigsten. <cite title="Source Title">By Georg Christoph Lichtenberg</cite>',
            'Die Zeit ist kein Geld. Aber den einen nimmt das Geld die Zeit und den anderen die Zeit das Geld. <cite title="Source Title">By Ron Kritzfeld</cite>',
            'An Zeit fehlt es uns vor allem dort, wo es uns am Wollen fehlt. <cite title="Source Title">By Ernst Ferstl</cite>',
            'Nichtstun macht nur dann Spaß, wenn man eigentlich viel zu tun hätte. <cite title="Source Title">By Noél Coward</cite>',
            'Die Zeit vergeht nicht schneller als früher, aber wir laufen eiliger an ihr vorbei. <cite title="Source Title">By George Orwell</cite>',
            'Zeitverschwendung ist die leichteste aller Verschwendungen. <cite title="Source Title">By Henry Ford</cite>',
            'Die Asiaten haben den Weltmarkt mit unlauteren Methoden erobert – sie arbeiten während der Arbeitszeit. <cite title="Source Title">By Ephraim Kishon</cite>',
            'Zukunft: die Zeit, von der man spricht, wenn man in der Gegenwart mit einem Problem nicht fertig wird. <cite title="Source Title">By Walter Hesselbach</cite>' +
            'An nichts gewöhnt man sich so schnell wie an das langsame Arbeiten. <cite title="Source Title">By Ernst R. Hauschka</cite>',
            'Wer von seinem Tag nicht zwei Drittel für sich selbst hat, ist ein Sklave. <cite title="Source Title">By Friedrich Nietzsche</cite>',
            'Zukunft: die Ausrede all jener, die in der Gegenwart nichts tun wollen. <cite title="Source Title">By Harold Pinter</cite>',
            'Zu haben was man will ist Reichtum, es aber ohne Reichtum tun, ist Kraft. <cite title="Source Title">By George Bernard Shaw</cite>',
            'Der Neid ist die aufrichtigste Form der Anerkennung. <cite title="Source Title">By Wilhelm Busch</cite>'
        ];

        let random = parseInt(Math.random() * (quotes.length - 1)),
            quote = quotes[random];

        $('footer blockquote footer').html('').append(quote);
    }

    /**
     * @param {Number|String|Array|Object|jQuery} _msg
     */
    static say(_msg)
    {
        console.log(_msg);
    }
}