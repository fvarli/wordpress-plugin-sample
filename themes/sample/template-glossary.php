<?php /* Template Name: Glossary Template */ ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Glossary Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php $get_all_glossary_list = get_all_glossary_list();
$alphabet_list = range('A', 'Z');
?>

<section class="glossary-title">
    <div class="container col-md-12 mt-4">
        <h1>Glossary Template</h1>
    </div>
</section>

<section class="glossary-search-area">
    <div class="container">
        <div class="row glossary-search-area-row">
            <div class="col-lg-8 col-md-12 col-sm-12 alphabet-area">
                <div>
                    <span class="b-text">Browse:</span>
                </div>
                <div class="alphabets">
                        <span class="alphabet-list">
                <?php foreach ($alphabet_list as $alphabet) { ?>
                    <?php echo '<a class="letter" data-id="'.$alphabet.'">'.$alphabet.'</a>'; ?>
                <?php } ?>
                <a class="letter" data-id="#">#</a>
                    </span>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 search-area">
                <form onsubmit="return false;" class="glossary-search-form" id="glossary-search-form">
                    <div>
                        <input type="text" class="search-input" placeholder="Enter keywords or phrases">
                        <div class="glossary-search-info">
                            <span class="glossary-search-info-image"></span>
                            <span class="glossary-search-info-text">Please enter 3 or more characters</span>
                        </div>
                        <div class="glossary-search-result">
                            <ul class="search-result-list">

                            </ul>
                        </div>
                    </div>

                    <input type="submit" class="glossary-search-button" value="Search">
                </form>
            </div>
        </div>
    </div>
</section>

<?php if(@$_GET['search'] || @$_GET['startswith']) { ?>
    <section class="glossary-result-info">
        <div class="container">
            <span class="search-result-number"><?php echo count($get_all_glossary_list)?></span> results found for: <span class="search-text"><?php echo @$_GET['search'] ? $_GET['search'] : $_GET['startswith'] ?></span>
        </div>
    </section>
<?php } ?>


<section class="glossary-list">
    <div class="container">
        <table class="table">
            <tbody>
            <?php
            //print_r($get_all_glossary_list);
            //print_r(get_search_query());
            //print_r($_GET);
            foreach ($get_all_glossary_list as $glossary) { ?>
                <tr>
                    <td class="title"><?php echo $glossary['title']; ?></td>
                    <td class="description"><?php echo $glossary['description']; ?>
                        <?php if(!empty($glossary['source'])) { ?>
                            <a href="<?php echo $glossary['source']; ?>" target="_blank" rel="noreferrer noopener nofollow">Source</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
</section>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    $('.letter').on('click', function () {
        var letter = $(this).attr('data-id');
        if(letter === "#") {
            window.location.href = '/glossary/';
        } else {
            window.location.href = '/glossary/?startswith='+letter;
        }
    });

    $(document).on("click", ".glossary-search-button", function (){
        var search_text = $('.search-input').val();
        window.location.href = '/glossary/?search=' + search_text;
    });

    $("input[class^='search-input']").on('change keydown paste input', function(event) {
        $('.glossary-search-info').show();
        $('.glossary-search-area .alphabet-area').css('margin-top','10px');
        var search_input = event.target.value;

        if (search_input.length <= 2) {
            $("input[type='submit']").attr('disabled', true);
            $('.glossary-search-info').show();
            $('.glossary-search-result').hide();
            $('.glossary-search-area .alphabet-area').css('margin-top','10px');
        } else {
            $("input[type='submit']").attr('disabled', false);
            $('.glossary-search-info').hide();
            $('.glossary-search-result').show();
            $('.glossary-search-area .alphabet-area').css('margin-top','10px');
            $('.glossary-search-area .alphabet-area').css('margin-bottom','unset');

            $.ajax({
                type: "POST",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                dataType: 'json',
                data: {
                    searchText: search_input,
                    action: 'get_glossary_list_by_search',
                },

                success: function(data){
                    listSearch(data);
                },
                error:function (e) {
                    console.log(e);
                }
            });
        }

    });

    function listSearch(data) {
        $('.search-result-list').html("");

        if(data.length === 0) {
            $('.search-result-list').append('<li class="search-result-no-found">No Found</li>');
        }

        for (var i = 0; i < data.length; i++) {
            $('.search-result-list').append('<li class="search-result-list-text">'+data[i].title+'</li>');
        }
    }

    $(document).on("click", ".search-result-list-text", function (){
        var search_selected_text = $(this).text();
        window.location.href = '/glossary/?search=' + search_selected_text;
    });

</script>
</html>
