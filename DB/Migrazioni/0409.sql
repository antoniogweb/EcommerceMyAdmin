update pages set add_in_sitemap = 'N' where tipo_pagina != '' and (tipo_pagina = 'RESI' or tipo_pagina = 'MARCHI' or tipo_pagina = 'HOME' or tipo_pagina = 'GRAZIE_NEWSLETTER' or tipo_pagina = 'GRAZIE' or tipo_pagina = 'ACCOUNT_ELIMINATO');
