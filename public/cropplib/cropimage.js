/** ---------------------------------------------------------------------------
	*											CropImage Plugin with JQuery
	* ---------------------------------------------------------------------------
	* Version: 1.2.1
	* Author: Fabrice K.E.M
	* Created: 10/06/2018
	* Updated: 27/06/2024
	* Repository: https://github.com/fabrice8/cropimage
	*/
(function( factory ){
	if( typeof define === 'function' && define.amd )
		define( [ 'jquery' ], factory ) // AMD. Register as an anonymous module.
		
	else if( typeof module === 'object' && module.exports )
		module.exports = factory( require('jquery') ) // Node/CommonJS
		
	else factory( jQuery ) // Browser globals
}( function( $ ){
	'use strict'

	var
	OUTBOUNDS_COLOR = {
		none: 'none',
		dark: 'rgba(20, 20, 20, .6)',
		light: 'rgba(250, 250, 250, .6)'
	},
	STATIC_CROP = false, // the get absolute input sizes
	AUTO_CROP = false, // scalable but keep the picture sizes format
	FREE_CROP = false // give ability to crop de image any how you want directly in the canvas
			
	if( typeof $ === 'undefined' || !$.hasOwnProperty( 'fn' ) )
		throw new Error( 'CropImage requires jQuery' )
	
	// Define as jQuery Plugin
	$.fn.cropimage = core
	
	// Touch screen JQuery support events binding
	$.fn.extend({
		touchend: function( fn ){ return fn ? this.bind( 'touchend', fn ) : this.trigger('touchend') },
		touchstart: function( fn ){ return fn ? this.bind( 'touchstart', fn ) : this.trigger('touchstart') },
		touchmove: function( fn ){ return fn ? this.bind( 'touchmove', fn ) : this.trigger('touchmove') }
	})

	function DisplayError( message ){
		$('.R-container').append('<div class="R-error">'+ message +'</div>')
		setTimeout( () => $('.R-container .R-error').remove(), 8000 )
	}
	
	function CreateCropBox( options ){
		// Create the resizing hoster block
		return `<div class="R-container">
							<div class="R-cover"></div>
							
							<div class="R-adapter">
								<canvas class="statCanvas"></canvas>
							
								<div class="R-cropper ${options.circleCrop ? 'circle' : ''} ${!options.noBorder ? 'border' : ''}">
									<canvas class="dynaCanvas"></canvas>
									
									${
										options.inBoundGrid ? 
										`<div class="R-grid">
											<div class="R-col-1"></div>
											<div class="R-col-2"></div>
											<div class="R-col-3"></div>
										
											<div class="R-raw-1"></div>
											<div class="R-raw-2"></div>
											<div class="R-raw-3"></div>
										</div>` : ''
									}
									
									<div class="R-corner-lt" data-action="lt-crop"></div>
									<div class="R-corner-rt" data-action="rt-crop"></div>
									<div class="R-corner-rb" data-action="rb-crop"></div>
									<div class="R-corner-lb" data-action="lb-crop"></div>
									
									<div class="R-side-left" data-action="l-crop"></div>
									<div class="R-side-top" data-action="t-crop"></div>
									<div class="R-side-right" data-action="r-crop"></div>
									<div class="R-side-bottom" data-action="b-crop"></div>
								</div>
							</div>
					</div>`
	}
	
	function Cropper( e, adapted, callback ){
		// Define the responsivity of the cropper ( cropr ) in function of the picture and his adaptation to the container
		var
		rendWidth = adapted.width,
		rendHeight = adapted.height,
		
		destinationWidth,
		destinationHeight
				
		if( e.ratio != 1 ){
			if( e.ratio < 1 ){
				// Si le width du format souhaité est inferieur au height
				
				// ----- ( axe X )
				destinationWidth = rendWidth < rendHeight ?
												// si l'image est HAUT
												( rendWidth / rendHeight ) > ( e.minWidth / e.minHeight ) ? // Test de sensibilité entre les rapports de l'image normal et du formatage
																				( e.minWidth * rendHeight ) / e.minHeight // Considerable par rapport au width
																				: rendWidth // Plus ou moins
												
												// si l'image est LARGE
												: e.minWidth < e.minHeight ? 
																( e.minWidth * rendHeight ) / e.minHeight // Considerable par rapport au height
																: rendWidth // Plus ou moins
				// ----- ( axe Y )
				destinationHeight = rendWidth < rendHeight ? 
											// si l'image est HAUT
											( rendWidth / rendHeight ) > ( e.minWidth / e.minHeight ) ?// Test de sensibilité entre les rapports de l'image normal et du formatage
																					rendHeight // Plus ou moins
																					: ( e.minHeight * rendWidth ) / e.minWidth // Considerable par rapport au width
																					
											// si l'image est LARGE 
											: e.minWidth < e.minHeight ? 
														rendHeight // Plus ou moins
														: rendWidth * ( e.minHeight / rendHeight ) // Considerable par rapport au height
			} else {
				// Si le width du format souhaité est superieur au height ( commentaire inverse )
				
				destinationWidth = rendWidth < rendHeight ?
																					rendWidth 
																					: ( rendWidth / rendHeight ) > ( e.minWidth / e.minHeight ) ? 
																																												( e.minWidth * rendHeight) / e.minHeight 
																																												: rendWidth
				destinationHeight = rendWidth < rendHeight ? 
																					( e.minHeight * rendWidth ) / e.minWidth 
																					: ( rendWidth / rendHeight ) > ( e.minWidth / e.minHeight ) ? 
																																												rendHeight 
																																												: rendWidth * ( e.minHeight / rendHeight )
			}
		}
		else destinationWidth = destinationHeight = rendWidth < rendHeight ? rendWidth : rendHeight
		
		callback({
			width: destinationWidth,
			height: destinationHeight,
			left: adapted.HzImage ? ( rendWidth - destinationWidth ) / 2 : 0,
			top: adapted.HzImage ? 0 : ( rendHeight - destinationHeight ) / 2
		})
	}
	
	function AdaptImg( e, CONTAINER, callback ){
		// Adapt the image format to the container ( adaptation by responsive )
		var 
		rendWidth = e.width,
		rendHeight = e.height,
		rendTop = 0,
		rendLeft = 0

		rendWidth *= CONTAINER.height() / e.height
		if( rendWidth > CONTAINER.width() )
			rendWidth = CONTAINER.width()

		rendHeight *= CONTAINER.width() / e.width
		if( rendHeight > CONTAINER.height() )
			rendHeight = CONTAINER.height()
			
		rendTop = ( CONTAINER.height() - rendHeight ) / 2
		rendLeft = ( CONTAINER.width() - rendWidth ) / 2
			
		callback({
			width: rendWidth, 
			height: rendHeight,
			left: rendLeft,
			top: rendTop,
			HzImage: e.width != e.height ? e.width > e.height : null
		})
	}
	
	function validateIMG( img, options, callback ){
		var 
		MIN_SIZES = { width: options.minWidth, height: options.minHeight }, // minimum size of image
		FORMAT
		
		if( /x/.test( options.imgFormat ) ){
			// Format 320x400, 1000/740, ...
			FORMAT = options.imgFormat.split('x')
			
			MIN_SIZES.width = Number( FORMAT[0] )
			MIN_SIZES.height = Number( FORMAT[1] )
			
			STATIC_CROP = true
			AUTO_CROP = false
			FREE_CROP = false

			$('.R-container [data-action]').hide()
		}
		else if( /[1-9]\/[1-9]/.test( options.imgFormat ) ){
			// Format 3/2, 1/6 ...
			FORMAT = options.imgFormat.split('/')
			
			/**
			 * Only one dimension (width or height) can be
			 * adjusted to the other respective of
			 * the defined width and height adaptive
			 * ratio
			 */
			if( MIN_SIZES.width ) MIN_SIZES.height = MIN_SIZES.width * ( Number( FORMAT[1] ) / Number( FORMAT[0] ) )
			else if( MIN_SIZES.height ) MIN_SIZES.width = MIN_SIZES.height * Number( FORMAT[0] ) / Number( FORMAT[1] )
			
			STATIC_CROP = false
			AUTO_CROP = true
			FREE_CROP = false
			
			$('.R-container [data-action]').show()
			$('.R-container [class^=R-side-]').hide()
		} 
		else {
			MIN_SIZES.width = MIN_SIZES.width || 10
			MIN_SIZES.height = MIN_SIZES.height || 10

			// automatic format and changeable
			STATIC_CROP = false
			AUTO_CROP = false
			FREE_CROP = true

			$('.R-container [data-action]').show()
		}
		
		img.width >= MIN_SIZES.width && img.height >= MIN_SIZES.height ?
						callback({
							width: img.width,
							height: img.height,
							minWidth: MIN_SIZES.width,
							minHeight: MIN_SIZES.height,
							ratio: Number( MIN_SIZES.width ) / Number( MIN_SIZES.height )
						})
						: DisplayError('This image is smaller than '+ MIN_SIZES.width +'x'+ MIN_SIZES.height )
	}

	function getImageSource( image ){
		return typeof image !== 'string' ? 
										window.URL.createObjectURL( image ) // String URL
										: image // Blob
	}
	
	function core( options, callback ){
		/**---------------------------------------- cropper input configurations ----------------------------------------**/
		var 
		OPTIONS = $.extend({
			image: false,
			imgFormat: 'auto', // Formats: 3/2, 200x360, auto
			minWidth: 0,
			minHeight: 0,
			device: 'all', // lg-md, sm-xs
			circleCrop: false, // true => circle, square ( by default )
			zoomable: true,
			zoomMax: 2,
			background: 'transparent', // transparent, custom
			inBoundGrid: true,
			outBoundColor: 'dark', // light, dark, none
			// deprecated
			btnDoneAttr: '.R-container .R-btn-done'
		}, options ),
		IMG_URL

		/**---------------------------------------- Create and init the cropper DOM components ----------------------------------------**/
		
		$(this).html( CreateCropBox( OPTIONS ) )
		
		let  
		_IMG_ = null,
		$_CONTAINER = $(".R-container"),
		$_ADAPTER = $(".R-adapter"),
		$_CROPPER = $(".R-cropper"),
		$_COVER = $(".R-cover"),
		$_TRIGGERS = $('[class^="R-side-"], [class^="R-corner-"]'),
		cropCanvas = null,
		staticCanvas = null,
		ctx_Static = null,
		ctx_Dynamic = null
		
		/**
		 * Mount image to crop and the canvas background
		 */
		function setImage( image ){
			OPTIONS.image = image

			_IMG_ = new Image()

			/**
			 * Load and init the new image created
			 */
			window.location.protocol == 'file:' ?
													console.warn('[CropImage] - Exporting cropped image might not work because of <file://> protocol')
													: _IMG_.crossOrigin = '*'
													
			_IMG_.onerror = function( error ){ console.error(`Error loading the image: ${error}`) }
			_IMG_.onload = function(){
				/*************** Validate input image and apply crop configurations ***************/
				validateIMG( _IMG_, OPTIONS, initialize )
				$(window).on('resize', function(){ validateIMG( _IMG_, OPTIONS, initialize ) })
			}

			IMG_URL =
			_IMG_.src = getImageSource( OPTIONS.image )
			
			$_CONTAINER.addClass( OPTIONS.background )
			$_CONTAINER.find('.R-error').remove()
		}

		/**
		 * Reset cropper to initial state
		 */
		function reset(){
			OPTIONS.image = false

			IMG_URL = ''
			_IMG_ = null
			
			ctx_Static.clearRect( 0, 0, ctx_Static.canvas.width, ctx_Static.canvas.height )
			ctx_Dynamic.clearRect( 0, 0, ctx_Dynamic.canvas.width, ctx_Dynamic.canvas.height )

			$_CONTAINER.find('.R-error').remove()
		}

		/**
		 * Adaptive calculation of border or no-border impacts on
		 * the crop canvas.
		 * 
		 * (-4) - to accommodate borders overflow impact at the right & bottom edges
		 * (+2) - to push left & top positions to compensate the border size deficit
		 */
		function borderWise( value, compensate = false ){
			return OPTIONS.noBorder ? value : value +( compensate ? 2 : - 4 )
		}

		/**---------------------------------------- init crop box elements variables ----------------------------------------**/

		function initialize( originDetails ){
			// Variable accessible outsite this function's scope
			cropCanvas = document.querySelector('.dynaCanvas')
			staticCanvas = document.querySelector('.statCanvas')
			
			ctx_Static = staticCanvas.getContext('2d')
			ctx_Dynamic = cropCanvas.getContext('2d')
			
			// static (container) and dynamic (cropper) canvas contexts
			ctx_Dynamic.imageSmoothingEnabled = true
			ctx_Dynamic.imageSmoothingQuality = 'high'
			
			/*************** Adapt the picture to the container ( responsive ) ***************/
			AdaptImg( originDetails, $_CONTAINER, function( ADAPTED ){
				// given the picture size to the static canvas
				staticCanvas.width = ADAPTED.width
				staticCanvas.height = ADAPTED.height

				// Cover only the space of the image
				$_COVER.css({
					left: ADAPTED.left +'px',
					top: ADAPTED.top +'px',
					right: ADAPTED.left +'px',
					bottom: ADAPTED.top +'px',
					background: OUTBOUNDS_COLOR[ OUTBOUNDS_COLOR.hasOwnProperty( OPTIONS.outBoundColor ) ? OPTIONS.outBoundColor : 'dark' ]
				})
				
				/*************** Position and the size of the image cropper in function of the container ***************/
				Cropper( originDetails, ADAPTED, function( CROPPED ){
					$_CROPPER.css({
						width: cropCanvas.width = borderWise( CROPPED.width ),
						height: cropCanvas.height = borderWise( CROPPED.height ),
						left: CROPPED.left +'px', 
						top: CROPPED.top +'px' 
					})
					
					/**---------------------------------------- init variables ----------------------------------------**/
					
					// Cropper moving limits
					var 
					MoveLimitLeft = 0,
					MoveLimitTop = 0,
					MoveLimitRight = ADAPTED.width - cropCanvas.width,
					MoveLimitBottom = ADAPTED.height - cropCanvas.height,
					
					// Cropper resizing limits
					CropLimitLeft = 0,
					CropLimitTop = 0,
					CropLimitRight = CropLimitLeft + ADAPTED.width,
					CropLimitBottom = CropLimitTop + ADAPTED.height,

					// Cropper minimun sizes
					MIN_WIDTH = originDetails.minWidth || $_CROPPER.width() / 2,
					MIN_HEIGHT = originDetails.minHeight || $_CROPPER.height() / 2,
					
					// transition informations variables
					NO_MOVE = false, // variable of transition between moving and resizing scale
					ZOOMING = { width: ADAPTED.width, height: ADAPTED.height, left: 0, top: 0 }, // init image zoom sizes and position
					MOVING = {}, // moving informations
					RESIZING = {}, // resizing informations
						
					// Static canvas zooming informations
					zoomUp = true,
					deffZoom = 0,
					zoom = 1
				
					/**---------------------------------------- init canvas images ----------------------------------------**/
					
					setTimeout( () => {
						ctx_Static.drawImage( _IMG_, 0, 0, ADAPTED.width, ADAPTED.height ); // Set picture into the static canvas
						$_ADAPTER.css({ left: ADAPTED.left, top: ADAPTED.top, width: ADAPTED.width, height: ADAPTED.height }) // init the cropper sizes and position
						
						// Load first shot of image into the dynamic canvas ( cropper )
						ctx_Dynamic.drawImage( staticCanvas, borderWise( CROPPED.left, true ), borderWise( CROPPED.top, true ), CROPPED.width, CROPPED.height, 0, 0, CROPPED.width, CROPPED.height )
					}, 10 )

					/**---------------------------------------- events ----------------------------------------**/
					
					$_CROPPER.mousedown( function(e){
						if( !NO_MOVE ){
							MOVING.t = $_CROPPER
							MOVING.x = e.pageX - $_CROPPER.position().left
							MOVING.y = e.pageY - $_CROPPER.position().top
						}
						
						MoveLimitRight = ADAPTED.width - $_CROPPER.width()
						MoveLimitBottom = ADAPTED.height - $_CROPPER.height()
					} )
					
					.dblclick( function( e ){
						// zooming container image
						if( !OPTIONS.zoomable ) return 
					
						zoom == 1 ? zoomUp = true : null
						zoom > ( OPTIONS.zoomMax - 0.5 ) ? zoomUp = false : null
						
						MOVING.ox = Math.floor( e.pageX - $_COVER.offset().left )
						MOVING.oy = Math.floor( e.pageY - $_COVER.offset().top )
								
						zooming( zoomUp )
					} )
					
					.touchstart( function(e){
						if( !NO_MOVE ){
							MOVING.t = $_CROPPER
							MOVING.x = e.originalEvent.touches[0].clientX - $_CROPPER.position().left
							MOVING.y = e.originalEvent.touches[0].clientY - $_CROPPER.position().top
						}
						
						MoveLimitRight = ADAPTED.width - $_CROPPER.width()
						MoveLimitBottom = ADAPTED.height - $_CROPPER.height()
					} )
					
					$_TRIGGERS.mousedown( function(e){
						NO_MOVE = true
						
						RESIZING.t = $(this)
						RESIZING.topHeight = $_CROPPER.position().top + $_CROPPER.height() // to calculate TOP by scale LEFT movement in AUTO RESIZING
					} )
					
					$(document).mouseup( function(){ stop() } )
					
					.mousemove( function(e){
						e.preventDefault()
						if( !STATIC_CROP && RESIZING.t ) resizing( e, RESIZING )
					} )
					
					.touchend( function(){ stop() } )
					
					$_ADAPTER.mousemove( function(e){
						e.preventDefault()
						if( MOVING.t ) moving( e, MOVING )
					} )
					
					.touchmove( function(e){
						e.preventDefault()
						
						if( MOVING.t )
							moving( e, MOVING, true )
						
						else if( RESIZING.t && ( RESIZING.x || RESIZING.y ) )
							resizing( e, RESIZING, true )
					} )
					
					// DEPRECATED: Trigger event when the resizing is declare as done
					$( OPTIONS.btnDoneAttr ).click( function(){ typeof callback == 'function' && callback( cropCanvas.toDataURL('image/jpeg') ) } )
					
					/**---------------------------------------- pilote functions ----------------------------------------**/
					
					function moving( e, MOVING, touch ){
						// moving cropper in the container
						
						// Cropper moving position
						var
						LEFT = ( touch ? e.originalEvent.touches[0].clientX : e.pageX ) - MOVING.x,
						TOP = ( touch ? e.originalEvent.touches[0].clientY : e.pageY ) - MOVING.y,
						M_X = ( LEFT >= MoveLimitLeft && LEFT <= borderWise( MoveLimitRight ) ),
						M_Y = ( TOP >= MoveLimitTop && TOP <= borderWise( MoveLimitBottom ) )
								
						M_X ? $_CROPPER.css( 'left', LEFT +'px' ) : LEFT = $_CROPPER.position().left
						M_Y ? $_CROPPER.css( 'top', TOP +'px' ) : TOP = $_CROPPER.position().top
						
						if( zoom > 1 ){
							// showing zoomed image ( out of the container sizes ) in function of the position of the mouse
							ctx_Static.clearRect( 0, 0, ZOOMING.width, ZOOMING.height );
							
							// mouse position
							MOVING.ox = Math.floor( e.pageX - $_COVER.offset().left )
							MOVING.oy = Math.floor( e.pageY - $_COVER.offset().top )
							
							// ration between original and zoomed image sizes
							// deffZoom = ( zoom - ( zoom > 1 ? ( zoom / 2 ) : 0 ) )
							
							ctx_Static.drawImage( _IMG_, -MOVING.ox * deffZoom, -MOVING.oy * deffZoom, ZOOMING.width, ZOOMING.height )
						}
						
						ctx_Dynamic.drawImage( staticCanvas, borderWise( LEFT, true ), borderWise( TOP, true ), $_CROPPER.width(), $_CROPPER.height(), 0, 0, $_CROPPER.width(), $_CROPPER.height() ) // image of this position
					}
					
					function resizing( e, RESIZING, touch ){
						// Cropper image resizing
						
						// cropper left and top side postion
						var 
						POS_X = ( touch ? e.originalEvent.touches[0].pageX : e.pageX ) - $_CROPPER.offset().left,
						POS_Y = ( touch ? e.originalEvent.touches[0].pageY : e.pageY ) - $_CROPPER.offset().top,
								
						// image relative position
						LEFT = ( touch ? e.originalEvent.touches[0].clientX : e.clientX ) - $_ADAPTER.offset().left,
						TOP = ( touch ? e.originalEvent.touches[0].clientY : e.clientY ) - $_ADAPTER.offset().top,
						
						SC_WIDTH,
						SC_HEIGHT
						
						switch( RESIZING.t.data('action') ){
							case 'l-crop': {
								SC_WIDTH = $_CROPPER.width() - POS_X;
							
								if( CropLimitLeft <= LEFT && SC_WIDTH > MIN_WIDTH ){
									
									$_CROPPER.css({ 'width': SC_WIDTH +'px', 'left': LEFT +'px' })
									cropCanvas.width = SC_WIDTH
									
									ctx_Dynamic.drawImage( staticCanvas, borderWise( LEFT, true ), borderWise( $_CROPPER.position().top, true ), SC_WIDTH, $_CROPPER.height(), 0, 0, SC_WIDTH, $_CROPPER.height() )
								}
							} break
							
							case 'r-crop': {
								SC_WIDTH = POS_X;

								if( borderWise( CropLimitRight ) >= LEFT && SC_WIDTH > MIN_WIDTH ){
									$_CROPPER.css( 'width', SC_WIDTH +'px' )
									cropCanvas.width = SC_WIDTH
									
									ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( $_CROPPER.position().top, true ), SC_WIDTH, $_CROPPER.height(), 0, 0, SC_WIDTH, $_CROPPER.height() )
								}
							} break

							case 't-crop': {
								SC_HEIGHT = $_CROPPER.height() - POS_Y;
														
								if( CropLimitTop <= TOP && SC_HEIGHT > MIN_HEIGHT ){
									
									$_CROPPER.css({ 'height': SC_HEIGHT +'px', 'top': TOP +'px' })
									cropCanvas.height = SC_HEIGHT
									
									ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), TOP, borderWise( $_CROPPER.width(), true ), SC_HEIGHT, 0, 0, $_CROPPER.width(), SC_HEIGHT )
								}
							} break
							
							case 'b-crop': {
								SC_HEIGHT = POS_Y;
														
								if( borderWise( CropLimitBottom ) >= TOP && SC_HEIGHT > MIN_HEIGHT ){
									$_CROPPER.css( 'height', SC_HEIGHT +'px' )
									cropCanvas.height = SC_HEIGHT
									
									ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( $_CROPPER.position().top, true ), $_CROPPER.width(), SC_HEIGHT, 0, 0, $_CROPPER.width(), SC_HEIGHT )
								}
							} break
							
							case 'lt-crop': {
								SC_WIDTH = $_CROPPER.width() - POS_X
								SC_HEIGHT = $_CROPPER.height() - POS_Y
									
								if( AUTO_CROP ){
									// proportional resizing ( width <=> height )
									
									if( CropLimitLeft <= LEFT && SC_WIDTH > MIN_WIDTH ){
										
										SC_HEIGHT = SC_WIDTH / originDetails.ratio
										TOP = RESIZING.topHeight - SC_HEIGHT
										
										if( CropLimitTop <= TOP && TOP <= borderWise( ADAPTED.height - SC_HEIGHT ) && SC_HEIGHT > MIN_HEIGHT ){
											
											$_CROPPER.css({ 'width': SC_WIDTH +'px', 'height': SC_HEIGHT +'px', 'left': LEFT +'px', 'top': TOP +'px' })
											cropCanvas.width = SC_WIDTH
											cropCanvas.height = SC_HEIGHT
											
											ctx_Dynamic.drawImage( staticCanvas, borderWise( LEFT, true ), borderWise( TOP, true ), SC_WIDTH, SC_HEIGHT, 0, 0, SC_WIDTH, SC_HEIGHT )
										}
									}
								} else {
									// free resizing
								
									if( CropLimitLeft <= LEFT && SC_WIDTH > MIN_WIDTH ){
										
										$_CROPPER.css({ 'width': SC_WIDTH +'px', 'left': LEFT +'px' })
										cropCanvas.width = SC_WIDTH
										
										ctx_Dynamic.drawImage( staticCanvas, borderWise( LEFT, true ), borderWise( $_CROPPER.position().top, true ), SC_WIDTH, $_CROPPER.height(), 0, 0, SC_WIDTH, $_CROPPER.height() )
									}
									
									if( CropLimitTop <= TOP && SC_HEIGHT > MIN_HEIGHT ){
										
										$_CROPPER.css({ 'height': SC_HEIGHT +'px', 'top': TOP +'px' })
										cropCanvas.height = SC_HEIGHT
										
										ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( TOP, true ), $_CROPPER.width(), SC_HEIGHT, 0, 0, $_CROPPER.width(), SC_HEIGHT )
									}
								}
							} break
							
							case 'lb-crop': {
								SC_WIDTH = $_CROPPER.width() - POS_X;
								SC_HEIGHT = POS_Y;
		
								if( AUTO_CROP ){
									// proportional resizing ( width <=> height )
									
									if( CropLimitLeft <= LEFT && SC_WIDTH > MIN_WIDTH ){
										
										SC_HEIGHT = SC_WIDTH / originDetails.ratio
										
										if( borderWise( CropLimitBottom ) >= TOP && SC_HEIGHT > MIN_HEIGHT && SC_HEIGHT < borderWise( ADAPTED.height - $_CROPPER.position().top ) ){
											
											$_CROPPER.css({ 'width': SC_WIDTH +'px', 'height': SC_HEIGHT +'px', 'left': LEFT +'px' })
											cropCanvas.width = SC_WIDTH
											cropCanvas.height = SC_HEIGHT
											
											ctx_Dynamic.drawImage( staticCanvas, borderWise( LEFT, true ), borderWise( $_CROPPER.position().top, true ), SC_WIDTH, SC_HEIGHT, 0, 0, SC_WIDTH, SC_HEIGHT )
										}
									}
								} else {
									// free resizing
									
									if( CropLimitLeft <= LEFT && SC_WIDTH > MIN_WIDTH ){
										
										$_CROPPER.css({ 'width': SC_WIDTH +'px', 'left': LEFT +'px' })
										cropCanvas.width = SC_WIDTH
										
										ctx_Dynamic.drawImage( staticCanvas, borderWise( LEFT, true ), borderWise( $_CROPPER.position().top, true ), SC_WIDTH, $_CROPPER.height(), 0, 0, SC_WIDTH, $_CROPPER.height() )
									}
									
									if( borderWise( CropLimitBottom ) >= TOP && SC_HEIGHT > MIN_HEIGHT ){
										
										$_CROPPER.css( 'height', SC_HEIGHT +'px' )
										cropCanvas.height = SC_HEIGHT
										
										ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( $_CROPPER.position().top, true ), $_CROPPER.width(), SC_HEIGHT, 0, 0, $_CROPPER.width(), SC_HEIGHT )
									}
								}
							} break
							
							case 'rt-crop': {
								SC_WIDTH = POS_X;
								SC_HEIGHT = $_CROPPER.height() - POS_Y;
	
								if( AUTO_CROP ){
									// proportional resizing ( width <=> height )
									
									if( borderWise( CropLimitRight ) >= LEFT && SC_WIDTH > MIN_WIDTH ){
										
										SC_HEIGHT = SC_WIDTH / originDetails.ratio
										TOP = RESIZING.topHeight - SC_HEIGHT
										
										if( CropLimitTop <= TOP && SC_HEIGHT > MIN_HEIGHT ){
											
											$_CROPPER.css({ 'width': SC_WIDTH +'px', 'height': SC_HEIGHT +'px', 'top': TOP +'px' })
											cropCanvas.width = SC_WIDTH
											cropCanvas.height = SC_HEIGHT
											
											ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( TOP, true ), SC_WIDTH, SC_HEIGHT, 0, 0, SC_WIDTH, SC_HEIGHT )
										}
										
										RESIZING.lastLeft = LEFT
									}
								} else {
									// free resizing
									
									if( borderWise( CropLimitRight ) >= LEFT && SC_WIDTH > MIN_WIDTH ){
										
										$_CROPPER.css( 'width', SC_WIDTH +'px' )
										cropCanvas.width = SC_WIDTH
										
										ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( $_CROPPER.position().top, true ), SC_WIDTH, $_CROPPER.height(), 0, 0, SC_WIDTH, $_CROPPER.height() )
									}
									
									if( CropLimitTop <= TOP && SC_HEIGHT > MIN_HEIGHT ){
										
										$_CROPPER.css({ 'height': SC_HEIGHT +'px', 'top': TOP +'px' })
										cropCanvas.height = SC_HEIGHT
										
										ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( TOP, true ), $_CROPPER.width(), SC_HEIGHT, 0, 0, $_CROPPER.width(), SC_HEIGHT )
									}
								}
							} break
							
							case 'rb-crop': {
								SC_WIDTH = POS_X;
								SC_HEIGHT = POS_Y;
	
								if( AUTO_CROP ){
									// proportional resizing ( width <=> height )
									
									if( borderWise( CropLimitRight ) >= LEFT && SC_WIDTH > MIN_WIDTH ){
										
										SC_HEIGHT = SC_WIDTH / originDetails.ratio
										
										if( borderWise( CropLimitBottom ) >= TOP && SC_HEIGHT > MIN_HEIGHT && SC_HEIGHT < borderWise( ADAPTED.height - $_CROPPER.position().top ) ){
											
											$_CROPPER.css({ 'width': SC_WIDTH +'px', 'height': SC_HEIGHT +'px' })
											cropCanvas.width = SC_WIDTH
											cropCanvas.height = SC_HEIGHT
											
											ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( $_CROPPER.position().top, true ), SC_WIDTH, SC_HEIGHT, 0, 0, SC_WIDTH, SC_HEIGHT )
										}
									}
								} else {
									// free resizing
										
									if( borderWise( CropLimitRight ) >= LEFT && SC_WIDTH > MIN_WIDTH ){
										
										$_CROPPER.css( 'width', SC_WIDTH +'px' )
										cropCanvas.width = SC_WIDTH
										
										ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( $_CROPPER.position().top, true ), SC_WIDTH, $_CROPPER.height(), 0, 0, SC_WIDTH, $_CROPPER.height() )
									}
									
									if( borderWise( CropLimitBottom ) >= TOP && SC_HEIGHT > MIN_HEIGHT ){
										
										$_CROPPER.css( 'height', SC_HEIGHT +'px' )
										cropCanvas.height = SC_HEIGHT
										
										ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( $_CROPPER.position().top, true ), $_CROPPER.width(), SC_HEIGHT, 0, 0, $_CROPPER.width(), SC_HEIGHT )
									}
								}
							} break
						}
					}
					
					function zooming( zoomUp ){
						// zoom container image
						ctx_Static.clearRect( 0, 0, ZOOMING.width, ZOOMING.height )
						
						if( zoomUp && zoom < OPTIONS.zoomMax ) zoom++ // zoom up
						else if( zoom > 1 ) zoom-- // zoom down
						
						// Zoomed image dimensions
						ZOOMING.width = ADAPTED.width * zoom
						ZOOMING.height = ADAPTED.height * zoom
						
						// ration between original and zoomed image sizes
						deffZoom = ( zoom - ( zoom > 1 ? ( zoom / 2 ) : 0 ) )
						
						// Zoomed image left & top position
						ZOOMING.left = zoom > 1 ? - MOVING.ox * deffZoom : 0
						ZOOMING.top = zoom > 1 ? - MOVING.oy * deffZoom : 0
						
						ctx_Static.drawImage( _IMG_, ZOOMING.left, ZOOMING.top, ZOOMING.width, ZOOMING.height )
						ctx_Dynamic.drawImage( staticCanvas, borderWise( $_CROPPER.position().left, true ), borderWise( $_CROPPER.position().top, true ), $_CROPPER.width(), $_CROPPER.height(), 0, 0, $_CROPPER.width(), $_CROPPER.height() )
					}
					
					function stop(){
						// init cropper moving and resizing informations
						
						NO_MOVE = false 
						MOVING = {}
						RESIZING = {}
					}
				} )
			} )
		}
		
		// Mount initial image
		OPTIONS.image && setImage( OPTIONS.image )

		return {
			setImage,
			getImage: function( format = 'jpeg' ){
				if( !IMG_URL ){
					DisplayError('Configuration Error: Undefined image URL or blob image file')
					return
				}

				return cropCanvas.toDataURL('image/'+ format.toLowerCase() )
			},
			reset
		}
	}

	return core
} ) )