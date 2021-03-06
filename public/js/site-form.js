var $collectionHolder;

// setup an "add a action" link
var $addButton = $('<button type="button" class="add_url_link btn btn-info">Ajouter une url</button>');
var $removeFormButton = $('.btn-action-remove');
var $newLinkLi = $('<div></div>').append($addButton);

jQuery(document).ready(function () {
    // Get the ul that holds the collection of actions
    $collectionHolder = $('.urls');

    // add the "add a action" anchor and li to the actions ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addButton.on('click', function (e) {
        e.preventDefault();
        addForm($collectionHolder, $newLinkLi, $(this));
    });

    // $collectionHolder.find('.url-item').each(function () {
    //     addFormDeleteLink($(this));
    // });

    $('body').on('click', '.btn-action-remove', function () {
        $(this).closest('.url-item').remove();
    })
});


function addForm($collectionHolder, $newRow) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li

    var $newForm = $(newForm);
    $newRow.before($newForm);
    // addFormDeleteLink($newForm);
}

function addFormDeleteLink($formRow) {
    // var $removeFormButton = $('<button type="button">Delete this tag</button>');
    // $tagFormLi.append($removeFormButton);
    // $removeFormButton.on('click', function (e) {
    //     // remove the li for the tag form
    //     $(this).closest('.url-item').remove();
    // });
}