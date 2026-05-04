<?php
/**
 * Template to display Author Box
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */

global $cl_options;
if( $cl_options['cepatlakoo_author_box_status'] == false )
    return;
    
$fname = get_the_author_meta('first_name');
$lname = get_the_author_meta('last_name');
$full_name = '';

if( empty($fname)){
    $full_name = $lname;
} elseif( empty( $lname )){
    $full_name = $fname;
} else {
    $full_name = "{$fname} {$lname}";
}

?>
<div class="article-widget author-box">
    <div class="author-box-inner">
        <div class="author-avatar">
            <a href="#" title="Visit <?php echo $full_name; ?>">
                <?php echo get_avatar( $post->post_author, '100' ); ?>
            </a>
        </div>

        <div class="avatar-info">
            <h3><?php echo $full_name; ?></h3>
            <div class="socials">
                <ul>
                    <?php if( get_the_author_meta( 'cl_social_media_facebook' ) ): ?>
                    <li>
                        <a href="<?php echo get_the_author_meta( 'cl_social_media_facebook' ); ?>" title="<?php echo $full_name; ?> Facebook page">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.5 13.75V8.75C22.5 7.37 23.62 6.25 25 6.25H27.5V0H22.5C18.3575 0 15 3.3575 15 7.5V13.75H10V20H15V40H22.5V20H27.5L30 13.75H22.5Z" fill="white"/>
                            </svg>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if( get_the_author_meta( 'cl_social_media_twitter' ) ): ?>
                    <li>
                        <a href="<?php echo get_the_author_meta( 'cl_social_media_twitter' ); ?>" title="<?php echo $full_name; ?> Twitter">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0)">
                            <path d="M12.5016 36.2583C27.5968 36.2583 35.8515 23.7524 35.8515 12.9083C35.8515 12.5531 35.8442 12.1994 35.8283 11.8475C37.4305 10.6891 38.8233 9.24345 39.922 7.59794C38.4516 8.25162 36.869 8.69138 35.2091 8.89005C36.9035 7.87382 38.2044 6.26676 38.8178 4.35086C37.2321 5.2908 35.4761 5.97379 33.6066 6.34275C32.1091 4.74759 29.9769 3.74997 27.616 3.74997C23.0842 3.74997 19.4089 7.42521 19.4089 11.9555C19.4089 12.5998 19.4809 13.226 19.6216 13.8269C12.8009 13.4836 6.75264 10.2182 2.70539 5.25235C2.00074 6.46512 1.59424 7.87412 1.59424 9.37742C1.59424 12.2244 3.04322 14.7381 5.24629 16.2085C3.89985 16.167 2.6352 15.7974 1.52954 15.1822C1.52832 15.2167 1.52832 15.2502 1.52832 15.2869C1.52832 19.2615 4.357 22.58 8.1125 23.3319C7.42281 23.5196 6.6971 23.6206 5.9485 23.6206C5.42054 23.6206 4.90602 23.5687 4.40583 23.4726C5.45076 26.7334 8.48055 29.1061 12.0725 29.1727C9.26363 31.3742 5.72511 32.6856 1.87958 32.6856C1.21796 32.6856 0.564271 32.6477 -0.078125 32.572C3.55378 34.8999 7.86684 36.2586 12.5019 36.2586" fill="white"/>
                            </g>
                            <defs>
                            <clipPath id="clip0">
                            <rect width="40" height="40" fill="white"/>
                            </clipPath>
                            </defs>
                            </svg>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if( get_the_author_meta( 'cl_social_media_instagram' ) ): ?>
                    <li>
                        <a href="<?php echo get_the_author_meta( 'cl_social_media_instagram' ); ?>" title="<?php echo $full_name; ?> Instagram page">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0)">
                            <path d="M20.0067 9.72998C14.3351 9.72998 9.74341 14.3266 9.74341 19.9933C9.74341 25.665 14.3401 30.2566 20.0067 30.2566C25.6784 30.2566 30.2701 25.66 30.2701 19.9933C30.2701 14.3216 25.6734 9.72998 20.0067 9.72998V9.72998ZM20.0067 26.655C16.3251 26.655 13.3451 23.6733 13.3451 19.9933C13.3451 16.3133 16.3267 13.3316 20.0067 13.3316C23.6867 13.3316 26.6684 16.3133 26.6684 19.9933C26.6701 23.6733 23.6884 26.655 20.0067 26.655V26.655Z" fill="white"/>
                            <path d="M28.2468 0.126651C24.5668 -0.0450151 15.4518 -0.0366818 11.7685 0.126651C8.5318 0.278318 5.6768 1.05998 3.37513 3.36165C-0.471532 7.20831 0.0201346 12.3916 0.0201346 19.9933C0.0201346 27.7733 -0.413199 32.8366 3.37513 36.625C7.2368 40.485 12.4951 39.98 20.0068 39.98C27.7135 39.98 30.3735 39.985 33.0985 38.93C36.8035 37.4916 39.6001 34.18 39.8735 28.2316C40.0468 24.55 40.0368 15.4366 39.8735 11.7533C39.5435 4.73165 35.7751 0.473318 28.2468 0.126651V0.126651ZM34.0718 34.08C31.5501 36.6016 28.0518 36.3766 19.9585 36.3766C11.6251 36.3766 8.28347 36.5 5.84513 34.055C3.0368 31.26 3.54513 26.7716 3.54513 19.9666C3.54513 10.7583 2.60013 4.12665 11.8418 3.65332C13.9651 3.57832 14.5901 3.55332 19.9351 3.55332L20.0101 3.60332C28.8918 3.60332 35.8601 2.67332 36.2785 11.9133C36.3735 14.0216 36.3951 14.655 36.3951 19.9916C36.3935 28.2283 36.5501 31.59 34.0718 34.08V34.08Z" fill="white"/>
                            <path d="M30.6767 11.7233C32.0012 11.7233 33.075 10.6496 33.075 9.325C33.075 8.00043 32.0012 6.92667 30.6767 6.92667C29.3521 6.92667 28.2783 8.00043 28.2783 9.325C28.2783 10.6496 29.3521 11.7233 30.6767 11.7233Z" fill="white"/>
                            </g>
                            <defs>
                            <clipPath id="clip0">
                            <rect width="40" height="40" fill="white"/>
                            </clipPath>
                            </defs>
                            </svg>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if( get_the_author_meta( 'cl_social_media_pinterest' ) ): ?>
                    <li>
                        <a href="<?php echo get_the_author_meta( 'cl_social_media_pinterest' ); ?>" title="<?php echo $full_name; ?> Pinterest">
                            <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.21733 0C2.83133 0.000666667 0.5 2.81067 0.5 5.87467C0.5 7.29533 1.294 9.068 2.56533 9.63C2.928 9.79333 2.88 9.594 3.192 8.40067C3.21667 8.30133 3.204 8.21533 3.124 8.12267C1.30667 6.02067 2.76933 1.69933 6.958 1.69933C13.02 1.69933 11.8873 10.0873 8.01266 10.0873C7.014 10.0873 6.27 9.30333 6.50533 8.33333C6.79066 7.178 7.34933 5.936 7.34933 5.10333C7.34933 3.00467 4.22267 3.316 4.22267 6.09667C4.22267 6.956 4.52666 7.536 4.52666 7.536C4.52666 7.536 3.52067 11.6 3.334 12.3593C3.018 13.6447 3.37667 15.7253 3.408 15.9047C3.42733 16.0033 3.538 16.0347 3.6 15.9533C3.69933 15.8233 4.91533 14.0887 5.256 12.8347C5.38 12.378 5.88866 10.5247 5.88866 10.5247C6.224 11.13 7.19066 11.6367 8.22066 11.6367C11.2847 11.6367 13.4993 8.94333 13.4993 5.60133C13.4887 2.39733 10.7467 0 7.21733 0V0Z" fill="#CDCDCD"/>
                            </svg>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if( get_the_author_meta( 'cl_social_media_youtube' ) ): ?>
                    <li>
                        <a href="<?php echo get_the_author_meta( 'cl_social_media_youtube' ); ?>" title="<?php echo $full_name; ?> Youtube Channel">
                        <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.1831 2.25079C15.0102 1.60818 14.5036 1.10162 13.8611 0.928589C12.6873 0.607422 7.992 0.607422 7.992 0.607422C7.992 0.607422 3.2969 0.607422 2.12311 0.916412C1.49295 1.08926 0.973938 1.60828 0.801086 2.25079C0.492188 3.4245 0.492188 5.85861 0.492188 5.85861C0.492188 5.85861 0.492188 8.30499 0.801086 9.46643C0.974121 10.1089 1.48059 10.6155 2.1232 10.7885C3.30926 11.1098 7.99218 11.1098 7.99218 11.1098C7.99218 11.1098 12.6873 11.1098 13.8611 10.8008C14.5037 10.6279 15.0102 10.1213 15.1833 9.47879C15.4921 8.30499 15.4921 5.87097 15.4921 5.87097C15.4921 5.87097 15.5044 3.4245 15.1831 2.25079ZM6.49713 8.10733V3.60989L10.4015 5.85861L6.49713 8.10733Z" fill="#CDCDCD"/>
                        </svg>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="user-bio">
            <?php echo nl2br( get_the_author_meta('description', $post->post_author) ); ?>
        </div>

        <footer class="user-action">
            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo sprintf(__('See all %s\'s posts', 'cepatlakoo'), $full_name); ?>"><?php echo sprintf(__('See all %s\'s posts', 'cepatlakoo'), $full_name); ?></a>
        </footer>
    </div>
</div>