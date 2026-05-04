<style>
/* Gaya untuk desktop (list) */
.store-category-list.desktop-only {
  list-style: none;
  padding: 0;
  margin: 0;
  display: block; /* Pastikan ditampilkan di desktop */
}

.store-category-list.desktop-only li {
  padding: 10px 14px;
  border-bottom: 1px solid #eee;
  transition: background 0.2s ease;
}

.store-category-list.desktop-only li:hover {
  background-color: #f9f9f9;
}

.store-category-list.desktop-only li a {
  text-decoration: none;
  color: #333;
  font-weight: 500;
  display: block;
  font-size: 15px;
}

/* Subkategori */
.store-category-list.desktop-only li ul {
  padding-left: 16px;
  margin-top: 6px;
  border-left: 2px solid #e5e5e5;
}

/* Gaya untuk mobile (dropdown) */
.store-category-list.mobile-only {
  display: none; /* Sembunyikan secara default */
  width: 100%;
  padding: 10px;
  font-size: 16px;
  margin: 10px 0;
}


/* Media query untuk mobile */
@media (max-width: 1024px) {
  .store-category-list.desktop-only {
    display: none; /* Sembunyikan list di mobile */
  }
  
  .store-category-list.mobile-only {
    display: block; /* Tampilkan dropdown di mobile */
  }
  
  .dokan-store-widget.dokan-store-menu .widget-title {
        display: none !important;
    }
}

/* Gaya khusus untuk iPhone kecil */
@media screen and (max-width: 375px) {
  .store-cat-stack-dokan {
    padding: 0 12px;
    margin-top: 10px;
  }
  
  .store-category-list.mobile-only {
    font-size: 14px;
    padding: 8px;
  }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const items = document.querySelectorAll('.desktop-only li');

  items.forEach(item => {
    const subList = item.querySelector('ul');
    const link = item.querySelector('a');

    if (subList) {
      item.classList.add('has-sub');
      subList.style.display = 'none';

      // Klik pada LI (kecuali link) akan toggle sublist
      item.addEventListener('click', function (e) {
        if (!link.contains(e.target)) { // kalau bukan klik link
          e.preventDefault();
          subList.style.display = subList.style.display === 'block' ? 'none' : 'block';
        }
      });

      // Klik link utama tapi tetap toggle sublist, tanpa reload
      link.addEventListener('click', function (e) {
        e.preventDefault(); // jangan langsung reload
        subList.style.display = subList.style.display === 'block' ? 'none' : 'block';
      });
    }
  });
});
</script>
<div id="dokan-secondary" class="dokan-store-sidebar" role="complementary">
    <?php if ( dokan_get_option( 'enable_theme_store_sidebar', 'dokan_appearance', 'off' ) === 'off' ) { ?>

        <div class="dokan-widget-area widget-collapse">
            <?php do_action( 'dokan_sidebar_store_before', $store_user->data, $store_info ); ?>
            <?php
            if ( ! dynamic_sidebar( 'sidebar-store' ) ) {
                $args = [
                    'before_widget' => '<aside class="widget dokan-store-widget %s">',
                    'after_widget'  => '</aside>',
                    'before_title'  => '<h3 class="widget-title">',
                    'after_title'   => '</h3>',
                ];

                dokan_store_category_widget();

                if ( ! empty( $map_location ) ) {
                    dokan_store_location_widget();
                }

                dokan_store_time_widget();
                dokan_store_contact_widget();
            }
            ?>

            <?php do_action( 'dokan_sidebar_store_after', $store_user->data, $store_info ); ?>
        </div>

    <?php } else { ?>
        <?php get_sidebar( 'store' ); ?>
    <?php } ?>

</div><!-- #secondary .widget-area -->
