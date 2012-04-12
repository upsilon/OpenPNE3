<?php echo javascript_tag('
openpne["relativeUrlRoot"] = "'.$sf_request->getRelativeUrlRoot().'";
') ?>

<?php echo stylesheet_tag('qunit.css') ?>
<?php echo javascript_include_tag('qunit.js') ?>
<?php echo javascript_include_tag('jquery.mockjax.js') ?>

<h2 id="qunit-banner"></h2>
<div id="qunit-testrunner-toolbar"></div>
<h2 id="qunit-userAgent"></h2>
<ol id="qunit-tests"></ol>

<div id="qunit-fixture">
</div>
