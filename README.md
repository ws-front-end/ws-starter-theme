**Lokaalse arenduskeskkona seadistamine**. Detailidesse laskumata:

1. lokaalne server – kõige lihtsam variant on nt XAMPP ([link](https://www.apachefriends.org/index.html)), aga ka Vagrant vms;
2. Wordpress'i installatsioon ([link](https://wordpress.org/download));
3. soovitatavalt ka Git, et faile lokaalse- ja dev-serveri vahel sünkroniseerida ja koostööd lihtsustada.
Meie dev-serveris _repository_'de (salvede) loomiseks: [Gitman](http://git.websystems.ee/gitman) ([juhis](http://git.websystems.ee/gitman/gitman.pdf)).

**Starter theme'i eesmärgiks on**:

- anda ette see osa, mida niikuinii vaja läheb (ja valikutena see, mida võib vaja minna);
- paremini organiseeritud kood, mida oleks projekti kasvades lihtsam hallata ja teistel mõista.

Reegel: kõik, mis siin on, olgu põhjendatud. Pigem vähem kui rohkem.

**WS Starter Theme kasutab Gulp'i** ([link](https://gulpjs.com/)) – _a toolkit for automating painful or time-consuming tasks_.

Gulp kompileerib SASSi üheks CSS-failiks, pakib (_minify_'b) CSSi ja JSi kokku, paneb sinu eest CSSi reeglitele brauserite _prefix_'id ette (kus vaja), _refresh_'ib muudatuste korral automaatselt veebilehte, liidab erinevatest SASSi failidest _media query_'id üheks, pakib pilte väiksemaks jne.

Gulp'i seaded asuvad failis _gulpfile.js_ – hetkel põhineb see WP Gulp'il ([link](https://github.com/ahmadawais/WPGulp/), kust leiab ka juhised, kuidas Gulp enda arvutisse installida).

**SASS** – sisuliselt CSS (ka täiesti tavaliselt kirjutatud CSS toimib SASS'i failis), aga rohkemate võimalustega (näiteks lubab muutujaid kasutada – [lisainfot](http://sass-lang.com/guide/)).

**Faili-/kaustamajandusest:**

Tööfailid (SASS, JS, pildid) asuvad kaustas _assets/src_,
Gulp'i poolt genereeritud failid (need, mis reaalselt veebilehele lähevad) kaustas _assets/dist_.

**SASSi failid** asuvad seal: _assets/src/sass_. Allolev struktuur on üpris subjektiivne – ilmselt on mõistlik jätta vabadus vabalt muuta/tõlgendada. Peaasi, et oleks mingigi loogika, millest teistel (ja ka endal) oleks võimalik aru saada.

- _base_ – üldised CSS-reeglid, mis peaksid laienema kõigile vastavatele elementidele (näiteks _ul_, _table_, _h2_ jms);
- _components_ – konkreetsed, eraldiseisvad, taaskasutatavad tükid (või moodulid või komponendid). Nii klasside kui ka failide nimetamisel on soovitatav järgida **BEM-metoodikat** ([rohkem infot](https://en.bem.info/methodology/)), aga sundida ei saa;
- _layout_ – raamistik, milles eelmises punktis mainitud komponendid istuvad. Näiteks: kontaktlehe üldine paigutus on _layout_, aga kontaktivorm on juba komponent;
Kõige lihtsamas variandis võib eelmised kaks kausta asendada nt ühe _custom_ kaustaga ja seal toimetada nii, nagu tarvis.
- _variables_ – projektis läbivalt kasutatud muutujad (näiteks _breakpoint_'id, värvid, fondid);
- _vendor_ – _third party_ (nt mingi _plugin_'iga kaasa tulnud) stiililehed.

Üksikud failid:

- _normalize.scss_ – CSSi _reset_, põhineb sellel: [link](https://necolas.github.io/normalize.css/);
- _utilities.scss_ – üldkasutatavad klassid/reeglid, mis tegelevad ainult väga konkreetsete stiiliküsimustega (nt _grid_ ja igasugused "align-middle"-laadsed asjad);
- _animations.scss_ – animatsioonid;
- _mixins.scss_ – imporditavad jupid.

- _main.scss_ – koondab/impordib kõik teised SASSi failid kokku. Gulp genereerib selle põhjal _dist_ kausta main.css ja main.min.css failid.

**Javascript'i failid** asuvad seal: _assets/src/js_. Alamkaustad:

- _custom_ (me endi kirjutatud skriptid)
- _vendor_ (_third party_ skriptid).

**Pildifailid:** _assets/src/img_. Gulp pakib väiksemaks ja salvestab _dist_ kausta.

**Fontidega** Gulp hetkel midagi ei tee, nii et need võib panna otse _dist_ kausta (_assets/dist/fonts_).

**Template'id (ehk PHP-/HTML-failid)**

Tasub üht-teist teada selle kohta, kuidas Wordpress'is _template_'ite loomine käib ([link](https://developer.wordpress.org/themes/basics/template-hierarchy/)),

aga on võimalik toimetada ka nii, et kirjutad _template_'itesse/PHP failidesse ainult tavalist HTMLi ja back-end arendaja paneb selle Wordpress'ile külge.
