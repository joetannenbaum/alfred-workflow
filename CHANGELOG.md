## v1.0.0 - 2022-08-24

This is a huge re-write of the existing library. It removes a lot of the "magic" that was happening in pre-1.0.0
releases
and opts instead for increased clarity and a better IDE experience. It also catches the library up to speed with the
latest Alfred
features.

For a complete upgrade guide, please
visit [https://www.alfredphpworkflows.com/docs/upgrading](https://www.alfredphpworkflows.com/docs/upgrading)

### Breaking

- Bump minimum PHP version to 7.4
- `result` method renamed to `item` ([docs](https://www.alfredphpworkflows.com/docs/upgrading#result-method-renamed))
- `mod` method refactored to accept less arguments and increase
  clarity ([docs](https://www.alfredphpworkflows.com/docs/upgrading#modifier-key-methods))
- `largetype` helper function renamed to `largeType`
- Argument order for the `text` method has been
  reversed ([docs](https://www.alfredphpworkflows.com/docs/upgrading#copy-and-large-text))
- `fileiconIcon` method renamed
  to `iconForFilePath` ([docs](https://www.alfredphpworkflows.com/docs/upgrading#icons))
- `filetypeIcon` method renamed
  to `iconForFileType` ([docs](https://www.alfredphpworkflows.com/docs/upgrading#icons))
- `type` method refactored to helper methods ([docs](https://www.alfredphpworkflows.com/docs/upgrading#item-type))
- `output` method now echoes JSON by default ([docs](https://www.alfredphpworkflows.com/docs/upgrading#outputting))
- Sorting is now a method off of the `items` method, and no longer returns an `item` (is no longer chainable)
  . ([docs](https://www.alfredphpworkflows.com/docs/upgrading#sorting))
- Filtering is now a method off of the `items` method, and no longer returns an `item` (is no longer chainable)
  . ([docs](https://www.alfredphpworkflows.com/docs/upgrading#filtering))

### Added

Full documentation for all of these new features can be found
at [alfredphpworkflows.com](https://www.alfredphpworkflows.com/).

- Ability to use combo modifier keys (e.g. `shift` + `cmd`)
- Ability to add an icon, arguments, and variables to a modifier key
- Ability to specify match text for items
- Ability to specify Universal Actions for items
- Ability to add variables by associative array
- Ability to read and write from a cache
- Ability to read and write data
- Item `invalid` helper method (as opposed to writing `valid(false)`)
- Logger to aid in debugging
- `argument` and `arguments` methods to base `Workflow` class to easily retrieve workflow arguments
- Ability to re-run workflow automatically after an interval
- `skipKnowledge` method
- Ability to easily retrieve Alfred-specific environment variables
- Ability to easily retrieve environment variables