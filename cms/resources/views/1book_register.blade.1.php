
    <script>
        
        
        
        var selected_tag_ids = [];
       
        var send_title = function(){
            $('#books_wraper').empty();
            const title = $('#book_title').val();
            const googleUrl = "https://www.googleapis.com/books/v1/volumes?q=intitle:" + title;
            $.getJSON(googleUrl, function (data) {
                for (i = 0; i < data.items.length; i++) {
                    console.log(data.items[i].volumeInfo);
                    
                    let book_img;
                    try {
                             book_img = data.items[i].volumeInfo.imageLinks.smallThumbnail;
                             if (data1[0].summary.cover == ""){ throw new Error() }
                        } catch(e) {
                            book_img = "http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg";
                        }
                        
                    $('#books_wraper').append('<div id="book' +i+ '" onClick="book_select('+i+')"><p class="titletitle">' +data.items[i].volumeInfo.title+ '</p><img class="imgimg" src="'+ book_img +'"></div>');
                }
            })
        }
        
        function book_select(num){
            $("#BookTitle").html('<input class="form-control input-lg" name="BookTitle" readonly="readonly" type="text"  value="' + $('#book' + num).children('.titletitle').text() +
                            '">');
            $("#BookThumbnail").html('<img src="'+$('#book' + num).children('.imgimg').attr('src')+'">');
            $('#books_wraper').empty();
        }
       
        var send_ISBN =function(){
            const isbn = $("#isbn").val();
            const googleUrl = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
            const openUrl   = "https://api.openbd.jp/v1/get?isbn=" + isbn;
          
           $.getJSON(openUrl , function (data1) {
                 if (data1[0]===null) {
                    $.getJSON(googleUrl, function (data) {
                        if (!data.totalItems){
                            $("#isbn").val("");
                            $("#BookTitle").text("");
                            $("#BookAuthor").text("");
                            $("#isbn10").text("");
                            $("#isbn13").text("");
                            $("#PublishedDate").text("");
                            $("#BookThumbnail").text("");
                            $("#BookDiscription").text("");
                            $("#BookImage").text("");
                            $("#message").html(' <p class = "bg-warning" id = "warning" > 該当する書籍がありません。 < /p>');
                            $('#message > p').fadeOut(3000);
                        } else {
                            //googleURLの処理
                           
                            $("#BookTitle").html('<input class="form-control input-lg" name="BookTitle" readonly="readonly" type="text"  value="' + data.items[0].volumeInfo.title +
                            '">');
                            $("#isbn10").html('<input class="form-control input-sm" name ="isbn10" readonly="readonly" type="hidden" value="' + data.items[0].volumeInfo.industryIdentifiers[
                                0].identifier + '">');
                            $("#isbn13").html('<input class="form-control input-sm" name ="isbn13" readonly="readonly" type="number" value="' + data.items[0].volumeInfo.industryIdentifiers[
                                1].identifier + '">');
                            $("#BookAuthor").html('<input class="form-control input-sm" name="BookAuthor" readonly="readonly" type="text" value="' + data.items[0].volumeInfo.authors +
                                '">');
                            $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                                .publishedDate + '">');
 
                            let description = "";
                         
                            $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                             .description + '">');
                            
                            
                            $("#Publisher").html('<input class="form-control input-sm" name="Publisher"  readonly="readonly" type="text" value="' 
                            + data.items[0].volumeInfo.publisher + '">');
      
                                 
                            try {
                                $("#BookThumbnail").html(' <img src=\"' + data.items[0].volumeInfo.imageLinks.smallThumbnail +
                                '\ " />'); 
                            } catch(e) {
            
                                $("#BookThumbnail").html('<img src="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg" width="100",heigt="200">');
                          
                            }
                            
                             try {
                                $("#BookImage").html(' <input name="BookImage" type="hidden" value="' + data.items[0].volumeInfo.imageLinks.smallThumbnail +
                                '">');
                            } catch(e) {
                                $("#BookImage").html('<input name="BookImage" type="hidden" value="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg">');
                            }
                        }
                    })
                } else {
    
                    //openURLの処理
                    console.log(data1[0]);
                        $("#BookTitle").html('<input class="form-control input-lg" name="BookTitle" readonly="readonly" type="text"  value="' + data1[0].summary.title +
                            '">');
                        $("#isbn10").html('<input class="form-control input-sm" name ="isbn10" readonly="readonly" type="hidden" value="' + data1[0].summary.isbn + 
                        '">');
                        $("#isbn13").html('<input class="form-control input-sm" name ="isbn13" readonly="readonly" type="number" value="' + data1[0].summary.isbn + 
                        '">');
                        $("#BookAuthor").html('<input class="form-control input-sm" name="BookAuthor" readonly="readonly" type="text" value="' + data1[0].summary.author +
                        '">');
                        $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' +data1[0].summary.pubdatey  +
                         '">');
                        $("#Publisher").html    ('<input class="form-control input-sm" name="Publisher" readonly="readonly" type="text" value="' +data1[0].summary.publisher  + 
                        '">');

                        let description = "";
                        if(data1[0].onix.CollateralDetail.TextContent){
                            description = data1[0].onix.CollateralDetail.TextContent[0].Text;
                        }else{
                            description = "書誌情報なし";
                        }
                        $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="'
                        + description + '">');
                       console.log(data1[0].summary.cover);
                     
                     
                        try {
                             $("#BookThumbnail").html('<img src=\"' + data1[0].summary.cover +'\ " />');   
                             if (data1[0].summary.cover == ""){ throw new Error() }
                        } catch(e) {
                            $("#BookThumbnail").html('<img src="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg" width="100",heigt="200">');
                        }

                        try {
                            $("#BookImage").html('<input name="BookImage" type="hidden" value="' + data1[0].summary.cover + '">');
                             if (data1[0].summary.cover == ""){ throw new Error() }
                        } catch(e) {
                            $("#BookImage").html('<input name="BookImage" type="hidden" value="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg">');
                        }
                }
            });
        };
      
      



 
