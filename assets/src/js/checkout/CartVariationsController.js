
class CartVariationsController {

  static findMatchingVariations( variations, attributes ) {
    var matching = [];
    for ( var i = 0; i < variations.length; i++ ) {
      var variation = variations[i];

      if ( CartVariationsController.isMatch( variation.attributes, attributes ) ) {
        matching.push( variation );
      }
    }
    return matching;
  }
  static isMatch( variation_attributes, attributes ) {
    var match = true;
    for ( var attr_name in variation_attributes ) {
      if ( variation_attributes.hasOwnProperty( attr_name ) ) {
        var val1 = variation_attributes[ attr_name ];
        var val2 = attributes[ attr_name ];
        if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
          match = false;
        }
      }
    }
    return match;
  }
}
