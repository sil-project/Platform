
$(document).ready(function () {
        
    displayStats($('#email-stats').data('stats'));
});

function displayStats(stats) {
    receiptsLoader(stats);
    linksLoader(stats);
    mostClicked(stats);
    leastClicked(stats);
}

//initializes classyloader for read receipts stats
function receiptsLoader(stats) {

    var receiptsOptions = {
        start: 'top',
        diameter: 50,
        height: 150,
        width: 150,
        lineWidth: 18,
        fontSize: '30px',
        fontFamily: 'Courier',
        fontColor: 'rgba(73, 125, 164, 1)',
        lineColor: 'rgba(73, 125, 164, 1)',
        remainingLineColor: 'rgba(73, 125, 164, 0.1)'
    };

    var loader = $('#receipts_loader');

    loader.ClassyLoader(receiptsOptions);

    loader.setPercent(stats.receipts);
    loader.draw();
}

//inititalizes classyloader for link clicks stats
function linksLoader(stats) {

    var linksOptions = {
        start: 'top',
        diameter: 50,
        height: 150,
        width: 150,
        lineWidth: 18,
        fontSize: '30px',
        fontFamily: 'Courier',
        fontColor: 'rgba(73, 125, 164, 1)',
        lineColor: 'rgba(73, 125, 164, 0.8)',
        remainingLineColor: 'rgba(73, 125, 164, 0.1)'
    };

    var loader = $('#links_loader');

    loader.ClassyLoader(linksOptions);

    loader.setPercent(stats.links.average);
    loader.draw();
}

//Inserts most clinked link stats in the view
function mostClicked(stats) {

    var link = stats.links.mostClicked.link;

    if (link) {
        html = '<p class="link">' + link + '</p> <span class="link_value">' + stats.links.mostClicked.value + ' % </span>';
    } else {
        html = '<p class="link">' + $('[data-source="no-links-clicked"]').data('target') + '</p>';
    }

    $('#most_clicked').append(html);
}

//Inserts least clinked link stats in the view
function leastClicked(stats) {

    var link = stats.links.leastClicked.link;

    if (link) {
        html = '<p class="link">' + link + '</p> <span class="link_value">' + stats.links.leastClicked.value + ' % </span>';
    } else {
        html = '<p class="link">' + $('[data-source="no-links-clicked"]').data('target') + '</p>';
    }
    $('#least_clicked').append(html);
}


