$(function() {
    "use strict";

    Tools.showRandomQuote();

    let config = new Config();
        config.load();
        config.init();

    let workingList = new WorkingList();
        workingList.load();

    let workingEdit = new WorkingEdit();
        workingEdit.init();

    let workingActions = new WorkingActions();
        workingActions.init();

    let timeAccount = new TimeAccount();
        timeAccount.init();
});