<div class="ie-alert" id="js-ie-alert">
    <div class="ie-alert__container">
        <div>
            <img src="<?php echo ThemeSetup::get_dist("img/svg/ie-logo.svg"); ?>" alt="Internet Explorer Icon">
        </div>

        <div class="ie-alert__container__content">
            <p><strong><?php _e('NB!', 'ws_theme')?></strong><?php _e(' Microsoft on loobunud Internet Exploreri arendamisest ning sellele uuenduste
                tegemisest ja ei soovita antud internetibrauserit turvanõrkuste tõttu kasutada. Internet Explorer ei
                toeta enam uusi võrgustandardeid ning antud veebilahendus ei tööta siinses brauseris korrektselt.', 'ws_theme')?>
            </p>

            <div class="ie-alert__container__content__browsers-links">
                <p><?php _e('Palun lae alla mõni moodne veebilehitseja:', 'ws_theme')?></p>

                <ul>
                    <li>
                        <a target="_blank" rel="noreferrer"
                            href="https://www.google.com/intl/et/chrome/"><?php _e('Google Chrome', 'ws_theme')?>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" rel="noreferrer"
                            href="https://www.mozilla.org/et/firefox/new/"><?php _e('Mozilla Firefox', 'ws_theme')?>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" rel="noreferrer"
                            href="https://www.microsoft.com/en-us/edge"><?php _e('Microsoft Edge', 'ws_theme')?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <button class="ie-alert__container__close" id="js-close-alert">&#10005;</button>
    </div>
</div>
