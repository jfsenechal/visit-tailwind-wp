<?php
namespace VisitMarche\ThemeTail;

use VisitMarche\Theme\Lib\Twig;

wp_footer();
Twig::rendPage(
    '@VisitTail/_footer.html.twig',
    [

    ]
);
echo '
</body>
</html>';