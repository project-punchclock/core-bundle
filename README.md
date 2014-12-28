#Volleyball - summercamp scheduling system
## Utility Bundle
This is a bundle utilizing the utility component of the Volleyball Scheduling system.

###Controllers
- AddressController
- HomepageController
- UtilityController

###Entities
- Address
- Carousel
- CarouselItem

###Form Types
- AddressFormType
- CarouselFormType
- CarouselItemFormType
- EntityToIdentifierType

###Menu Builders
- AdminMenuBuilder
- AttendeeMenuBuilder
- BaseBuilder
- FacultyMenuBuilder
- LeaderMenuBuilder
- MenuBuilder

###Routes
Name | Path | Parameters
--- | --- | ---
dashboard | /dashboard |
homepage | / | 
volleyball_about | /about | 
volleyball_address_edit | /addresses/{slug}/edit | Address.slug
volleyball_address_show | /addresses/{slug} | Address.slug
volleyball_contact | /contact | 
volleyball_help | /help | 

###Services


###Traits
- BlameableTrait
- EntityBootstrapTrait
- GeolocatableTrait
- SluggableTrait
- TimestampableTrait
- TranslatableTrait

###Voters
- RequestVoter