(function($) {

$.ajaxSetup({ async: false });
$.mockjaxSettings["responseTime"] = 0;

var brand = $("a.brand");
var nc_button = $("img.ncbutton");
var post_button = $("img.postbutton");

var navigation = $("div.nav-collapse");
var ncform = $("div.ncform");
var postform = $("div.postform");

module("tosaka", {
  teardown: function() {
    navigation.hide();
    ncform.hide();
    postform.hide();
  }
});

function assert_tosaka_collapse(_navigation, _ncform, _postform) {
  ok(navigation.is(_navigation));
  ok(ncform.is(_ncform));
  ok(postform.is(_postform));
}

test("expand toggle (navigation)", function() {
  expect(9);

  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");

  brand.click();
  assert_tosaka_collapse(":visible", ":hidden", ":hidden");
  brand.click();
  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");
});


test("expand toggle (navigation, close)", function() {
  expect(6);

  brand.click();

  $(".toggle1_close", navigation).click();
  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");

  brand.click();
  assert_tosaka_collapse(":visible", ":hidden", ":hidden");
});


test("expand toggle (ncform)", function() {
  expect(9);

  var mock = $.mockjax({
    url: openpne.apiBase + 'push/search.json',
    responseText: { "status": "success", "data": [] }
  });

  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");

  nc_button.click();
  assert_tosaka_collapse(":hidden", ":visible", ":hidden");
  nc_button.click();
  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");

  $.mockjaxClear(mock);
});


test("expand toggle (ncform, close)", function() {
  expect(6);

  var mock1 = $.mockjax({
    url: openpne.apiBase + 'push/search.json',
    responseText: { "status": "success", "data": [] }
  });
  var mock2 = $.mockjax({
    url: openpne.apiBase + 'push/search.json',
    responseText: { "status": "success", "data": [] }
  });

  nc_button.click();
  $(".toggle1_close", ncform).click();
  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");

  nc_button.click();
  assert_tosaka_collapse(":hidden", ":visible", ":hidden");

  $.mockjaxClear(mock1);
  $.mockjaxClear(mock2);
});


test("expand:visible toggle (post_button)", function() {
  expect(9);

  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");

  post_button.click();
  assert_tosaka_collapse(":hidden", ":hidden", ":visible");
  post_button.click();
  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");
});


test("expand:visible toggle (post_button)", function() {
  expect(6);

  post_button.click();
  $(".toggle1_close", postform).click();
  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");

  post_button.click();
  assert_tosaka_collapse(":hidden", ":hidden", ":visible");
});


test("expand:visible toggle (mixed)", function() {
  expect(15);

  var mock = $.mockjax({
    url: openpne.apiBase + 'push/search.json',
    responseText: { "status": "success", "data": [] }
  });

  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");

  brand.click();
  assert_tosaka_collapse(":visible", ":hidden", ":hidden");
  nc_button.click();
  assert_tosaka_collapse(":hidden", ":visible", ":hidden");
  post_button.click();
  assert_tosaka_collapse(":hidden", ":hidden", ":visible");
  post_button.click();
  assert_tosaka_collapse(":hidden", ":hidden", ":hidden");

  $.mockjaxClear(mock);
});


test("ncform ajax", function() {
  expect(5);

  var mock = $.mockjax({
    url: openpne.apiBase + 'push/search.json',
    responseText: {
      "status": "success",
      "data": [
        {
          "id": 1,
          "body": "notify1",
          "category": "other",
          "unread": true,
          "created_at": "",
          "icon_url": openpne.relativeUrlRoot + "/images/no_image.gif",
          "url": "http://example.com/",
          "member_id_from": 1,
        },
        {
          "id": 2,
          "body": "notify2",
          "category": "message",
          "unread": true,
          "created_at": "",
          "icon_url": openpne.relativeUrlRoot + "/images/no_image.gif",
          "url": "http://example.com/",
          "member_id_from": 1,
        },
        {
          "id": 3,
          "body": "notify3",
          "category": "link",
          "unread": true,
          "created_at": "",
          "icon_url": openpne.relativeUrlRoot + "/images/no_image.gif",
          "url": "http://example.com/",
          "member_id_from": 1,
        }
      ]
    }
  });

  nc_button.click();

  var push = ncform.find("#pushList > .push");
  equal(push.length, 3);

  ok(push.eq(0).is(".nclink"));
  ok(push.eq(1).is(".nclink"));
  ok(push.eq(2).is(":not(.nclink)"));

  equal(push.eq(2).find(".friend-accept, .friend-reject").length, 2);

  $.mockjaxClear(mock);
});


test("ncform ajax (failtest1)", function() {
  expect(1);

  var mock = $.mockjax({
    url: openpne.apiBase + 'push/search.json',
    status: 500,
    responseText: "Internal Server Error"
  });

  nc_button.click();
  ok($("#pushLoading").is(":hidden"));

  $.mockjaxClear(mock);
});


test("ncform ajax (failtest2)", function() {
  expect(1);

  var mock = $.mockjax({
    url: openpne.apiBase + 'push/search.json',
    responseText: {
      "status": "error",
      "data": []
    }
  });

  nc_button.click();
  ok($("#pushLoading").is(":hidden"));

  $.mockjaxClear(mock);
});

})(jQuery);
