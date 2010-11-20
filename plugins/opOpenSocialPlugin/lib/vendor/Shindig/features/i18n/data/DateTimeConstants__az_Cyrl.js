/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

var gadgets = gadgets || {};

gadgets.i18n = gadgets.i18n || {};

gadgets.i18n.DateTimeConstants = {
  ERAS:["e.\u0259.","b.e."],
  ERANAMES:["eram\u0131zdan \u0259vv\u0259l","bizim eram\u0131z\u0131n"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["\u0458\u0430\u043d\u0432\u0430\u0440","\u0444\u0435\u0432\u0440\u0430\u043b","\u043c\u0430\u0440\u0442","\u0430\u043f\u0440\u0435\u043b","\u043c\u0430\u0439","\u0438\u0458\u0443\u043d","\u0438\u0458\u0443\u043b","\u0430\u0432\u0433\u0443\u0441\u0442","\u0441\u0435\u043d\u0442\u0458\u0430\u0431\u0440","\u043e\u043a\u0442\u0458\u0430\u0431\u0440","\u043d\u043e\u0458\u0430\u0431\u0440","\u0434\u0435\u043a\u0430\u0431\u0440"],
  SHORTMONTHS:["yan","fev","mar","apr","may","iyn","iyl","avq","sen","okt","noy","dek"],
  WEEKDAYS:["\u0431\u0430\u0437\u0430\u0440","\u0431\u0430\u0437\u0430\u0440 \u0435\u0440\u0442\u04d9\u0441\u0438","\u0447\u04d9\u0440\u0448\u04d9\u043d\u0431\u04d9 \u0430\u0445\u0448\u0430\u043c\u044b","\u0447\u04d9\u0440\u0448\u04d9\u043d\u0431\u04d9","\u04b9\u04af\u043c\u04d9 \u0430\u0445\u0448\u0430\u043c\u044b","\u04b9\u04af\u043c\u04d9","\u0448\u04d9\u043d\u0431\u04d9"],
  SHORTWEEKDAYS:["B.","B.E.","\u00c7.A.","\u00c7.","C.A.","C","\u015e."],
  NARROWWEEKDAYS:["7","1","2","3","4","5","6"],
  SHORTQUARTERS:["1-ci kv.","2-ci kv.","3-c\u00fc kv.","4-c\u00fc kv."],
  QUARTERS:["1-ci kvartal","2-ci kvartal","3-c\u00fc kvartal","4-c\u00fc kvartal"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, d, MMMM, y","d MMMM , y","d MMM, y","yy/MM/dd"],
  TIMEFORMATS:["HH:mm:ss zzzz","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 6,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 2
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
