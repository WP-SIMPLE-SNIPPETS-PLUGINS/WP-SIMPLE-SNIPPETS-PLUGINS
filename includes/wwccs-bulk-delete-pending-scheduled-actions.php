add_filter( 'woocommerce_debug_tools', 'wwccs_bulk_delete_pending_scheduled_actions' );
 
function wwccs_bulk_delete_pending_scheduled_actions( $tools ) {    
    $tools['clean_pending_actions'] = [
        'name' => 'Clean Pending Actions',
        'button' => 'Run',
        'desc' => 'Deletes all pending Action Scheduler actions.',
        'callback' => function() {
            global $wpdb;
            $table = $wpdb->prefix . 'actionscheduler_actions';
            $batch_size = 10000;
            $deleted = 0;
            do {
                $rows_deleted = $wpdb->query( "DELETE FROM $table WHERE status = 'pending' LIMIT $batch_size" );
                $deleted += $rows_deleted;
            } while ( $rows_deleted > 0 );
            return sprintf( 'Deleted %d pending actions.', $deleted );
        }
    ];   
    return $tools;
}
