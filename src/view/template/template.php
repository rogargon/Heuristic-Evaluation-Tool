<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/js/bootstrap-colorpicker.min.js"></script>
<button id="topButton" title="Go to top"><span class="glyphicon glyphicon-menu-up"></span></button>
<div class="row">

  <div class="row">
      <div class="col-lg-12 margin-lg-t">
        <?php if (!$this->editTemplate): ?>
          <h1 class="page-header"><?= $this->template->getName();?></h1>
        <?php else: ?>
          <div class="form-group">
            <input name="template" type="text" class="form-control input-lg" placeholder="Template name" value="<?= $this->template->getName();?>" >
          </div>
        <?php endif; ?>
      </div>
  </div>

  <?php if (isset($this->error)) { ?>
    <div class="alert alert-danger" role="alert">
     <?=$this->error; ?>
    </div>
  <?php } ?>

<?php if ($this->editTemplate): ?>
<div class="modal fade" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="/template/category-new/" method="POST">
        <div class="modal-header">
          <strong>New  Category </strong>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="category">Category Name:</label>
            <input name="categoryName" type="text" class="form-control" placeholder="Insert category name" />
            <input name="idTemplate" type="hidden" class="form-control" value="<?= $this->template->getId(); ?>" />
            <div class="margin-lg-t"></div>

            <label for="question">Questions:</label>
            <div id="questions">
              <input name="questionName_0" type="text" class="form-control" placeholder="Insert question">
            </div>
            <input id="questionsCount" type="hidden" value="0" name="questionsCount" />
            <a id="addQuestion" href="#" class="btn btn-sm btn-secondary margin-lg-t"><span class="glyphicon glyphicon-plus"></span> Add new question</a>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" ><span class="glyphicon glyphicon-floppy-disk"></span> Save </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg">
      <div class="right">
        <?php if ($this->editTemplate):
            if ($this->template->isActive() == 0):?>
              <a href="/template/active/<?=$this->template->getId();?>?edit=1" title="Active template"  class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> Active</a>
            <?php else: ?>
              <a href="/template/active/<?=$this->template->getId();?>?edit=0" title="Inactive template"  class="btn btn-default"><span class="glyphicon glyphicon-ban-circle"></span> Inactive</a>
            <?php endif; ?>
        <?php endif; ?>
      </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <!-- Nav tabs -->
            <?php
              if ($this->tab == 0) {
                $active0 = "active";
                $active1 = "";
              } else {
                $active0 = "";
                $active1 = "active";
              }
            ?>
            <ul class="nav nav-tabs">
                <li class="<?php echo $active0; ?>">
                  <a href="#categories" data-toggle="tab">Categories</a>
                </li>
                <li class="<?php echo $active1; ?>">
                  <a href="#answers" data-toggle="tab">Answers</a>
                </li>
            </ul>
            <!-- Tab panes -->

            <div class="tab-content">
                <div class="tab-pane fade in <?php echo $active0; ?>" id="categories">
                        <?php if ($this->editTemplate): ?>
                          <div class="form-group margin-r margin-rg-t"></br>
                            <a data-toggle="modal" data-target="#newCategoryModal" href="#" title="Add new Category" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add new category</a>
                          </div>
                        <?php endif; ?>

                          <?php
                              if(!empty($this->template->getCategories())):
                                foreach ($this->template->getCategories() as $category) { ?>
                                    <table class="table">
                                      <tbody>
                                        <tr>
                                          <td width="70%" style="border-bottom: 1px solid #ddd ; border-top: 0px solid #ddd"><h4> <?=++$c.". ". $category->getName(); ?></h4></td>
                                          <?php if ($this->editTemplate):?>
                                            <td width="10%" style="border-bottom: 1px solid #ddd ; border-top: 0px solid #ddd">
                                              <form action="/template/category-remove/" method="POST" class="text-center">
                                                <input name="idTemplate" type="hidden" class="form-control"  value="<?= $this->template->getId()?>"/>
                                                <input name="idCategory" type="hidden" class="form-control"  value="<?= $category->getId()?>"/>
                                                <button type="submit" class="btn btn-danger">Remove category</button>
                                              </form>
                                            </td>
                                          <?php endif; ?>
                                        </tr>
                                        <?php foreach ($category->getQuestions() as $question) { ?>
                                        <tr>
                                          <td width="70%"><?= $question->getName(); ?></td>
                                          <?php if ($this->editTemplate):?>
                                          <td width="10%">
                                            <form action="/template/question-remove" method="POST" class="text-center">
                                              <input type="hidden" value="<?=$this->template->getId();?>" name="id_template" />
                                              <input type="hidden" value="<?=$question->getId();?>" name="id_question" />
                                              <button type="submit" class="btn btn-secondary text-danger" style="background-color:white;" title="Remove question"><span class="glyphicon glyphicon-remove"></span></button>
                                            </form>
                                          </td>
                                          <?php endif; ?>
                                        </tr>
                                        <?php } ?>
                                      </tbody>
                                    </table>
                                    <form action="/template/question-new" method="POST">
                                    <?php if ($this->editTemplate): ?>
                                        <div class="input-group col-xs-6">
                                          <input name="idCategory" type="hidden" class="form-control"  value="<?= $category->getId()?>" />
                                          <input name="idTemplate" type="hidden" class="form-control"  value="<?= $this->template->getId()?>" />
                                          <div class="input-group">
                                            <input name="questionName" type="text" class="form-control" placeholder="Insert question">
                                            <span class="input-group-btn">
                                              <button class="btn btn-default" type="submit"  title="Add question"><span class="glyphicon glyphicon-plus"></span></button>
                                            </span>
                                          </div>
                                        </div>
                                      </form>

                                    <?php endif; ?>
                          <?php } ?>

                          <?php else:?>
                            <div class="container">
                              <div class="alert alert-info" role="alert">
                                  <h4 class="alert-heading">Empty template!</h4>
                                  <p>You can create new categories </p>
                                  <hr>
                                  <p class="mb-0">Enter each category more questions and don't forget add your answer in next tab</p>
                              </div>
                            </div>
                        <?php  ?>
                      <?php endif;?>

                </div>


                <div class="tab-pane fade in <?php echo $active1 ; ?>" id="answers">
                    <div class="form-group margin-lg-t">
                          <table class="table" style="width:50%">
                              <thead class="thead-light">
                                <tr>
                                  <th scope="col">Answer's name</th>
                                  <th scope="col">Value</th>
                                  <th scope="col">Colour</th>
                                  <?php if ($this->editTemplate):?>
                                  <th scope="col"></th>
                                  <?php endif;?>
                                </tr>
                              </thread>
                              <tbody>
                              <?php if(!empty($this->template->getAnswers())):
                                 foreach ($this->template->getAnswers() as $answer) { ?>
                                <tr>
                                  <td style="border-bottom: 1px solid #ddd ; border-top: 0px solid #ddd"> <?= $answer->getName();?></td>
                                  <td style="border-bottom: 1px solid #ddd ; border-top: 0px solid #ddd"> <?= $answer->getValue();?></td>
                                  <td style="border-bottom: 1px solid #ddd ; border-top: 0px solid #ddd"><span class="glyphicon glyphicon-tint" style="color:<?= $answer->getColor();?>"></span></td>
                                  <?php if ($this->editTemplate):?>
                                  <td style="border-bottom: 1px solid #ddd ; border-top: 0px solid #ddd">
                                    <form action="/template/answer-remove" method="POST">
                                      <input type="hidden" value="<?=$this->template->getId();?>" name="id_template" />
                                      <input type="hidden" value="<?=$answer->getId();?>" name="id_answer" />
                                      <button type="submit" class="btn btn-secondary text-danger" style="background-color:white;" title="Remove answer"><span class="glyphicon glyphicon-remove"></span></button>
                                    </form>
                                  </td>
                                  <?php endif; ?>
                                </tr>
                                  <?php } ?>
                                <?php endif;?>
                              </tbody>
                          </table>
                    </div>
                    <br />
                      <?php if ($this->editTemplate): ?>

                            <table class="table" style="width:50%">
                              <tbody>
                                  <tr>
                                      <form action="/template/answer-new" method="POST">
                                      <input name="idTemplate" type="hidden" class="form-control" value="<?=$this->template->getId();?>" ></td>
                                        <td>
                                            <input name="answer" type="text" class="form-control" placeholder="Insert answer..." ></td>
                                        <td>
                                          <input name="value" type="number" step="0.25" class="form-control" placeholder="Insert value..." ></td>
                                        <td>
                                          <div id="cp2" class="input-group colorpicker-component" style="cursor:pointer">
                                              <input name="color"type="hidden" class="form-control" />
                                              <span class="input-group-addon" style="background-color:white"><i></i></span>
                                              <script>
                                                  $(function() {
                                                      $('#cp2').colorpicker();
                                                  });
                                              </script>
                                          </div>
                                        </td>

                                      <td >
                                        <button class="btn btn-outline-secondary" type="submit">+</button></td>
                                      </form>
                                  </tr>

                              </tbody>
                          </table>

                      <?php endif ?>

                </div>
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
</div>

<script>
  // Top Button
  window.onscroll = function() {scrollFunction()};
  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      $("#topButton").fadeIn( "slow");
    } else {
      $("#topButton").fadeOut( "slow");
    }
  }
  $("#topButton").click(function(){
    $("html, body").animate({ scrollTop: 0 }, "slow");
  });

  var questionId = 0;

  $("#addQuestion").click(function() {
    questionId++;
    var html = '<input name="questionName_' + questionId + '" type="text" class="form-control margin-lg-t" placeholder="Insert question">';
    $("#questions").append(html);
    $("#questionsCount").val(questionId);
  })
</script>
