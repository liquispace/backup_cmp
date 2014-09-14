<?php
	
	// Config
	require 'config/config.php';
	require 'config/paths.php';
	require 'src/lw.php';
	
	
	// Composer autoload
	require 'vendor/autoload.php';
	
	
	// Use classes
	use Cliprz\Filesystem\Filesystem as Filesystem;
	
	
	// Create a log channel
	$log = new Lw();
	
	
	// Start process
	$log->info('Started');
	$Filesystem = new Filesystem();
	
	
	// Directory copy
	function copy_directory( $source, $destination ) {
		global $log;
		if ( is_dir( $source ) ) {
			mkdir($destination);
			$directory = dir($source);
			while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
				if ( $readdirectory == '.' || $readdirectory == '..' ) {
					continue;
				}
				$PathDir = $source . '/' . $readdirectory; 
				if ( is_dir( $PathDir ) ) {
					copy_directory( $PathDir, $destination . '/' . $readdirectory );
					continue;
				}
				try {
					copy( $PathDir, $destination . '/' . $readdirectory );
				} catch (\ErrorException $e) {
					$log->error($e->getMessage());
				}
			}
			$directory->close();
		}else {
			try {
				copy( $source, $destination );
			} catch (\ErrorException $e) {
				$log->error($e->getMessage());
			}
		}
	}
	
	
	// Error handler
	function errorHandler($errno, $errmsg, $errfile)
	{
		global $log;
		$log->error($errmsg);
	}
	set_error_handler("errorHandler");
	
	
	// Copying loop
	$now = date('YmdHis');
	foreach ($paths as $dest => $src)
	{
		$source = $src;
		mkdir(DESTINATION_ROOT.$now);
		$destination = DESTINATION_ROOT.$now.DIRECTORY_SEPARATOR.$dest;
		$log->info('Copying '.$source.'...');
		copy_directory( $source, $destination );
		$log->info('Copied '.$source);
		$log->info('Memory usage: '.memory_get_peak_usage());
	}
	
	
	// Creating an archive
	$log->info('Creating an archive...');
	
	$log->info('Archive is created');
	$log->info('Memory usage: '.memory_get_peak_usage());
	$Filesystem->format(DESTINATION_ROOT.$now);
	$log->info('Finished');
	
	