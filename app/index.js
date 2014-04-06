'use strict';
var util = require('util');
var path = require('path');
var yeoman = require('yeoman-generator');


var AbbWpthemeGenerator = module.exports = function AbbWpthemeGenerator(args, options, config) {
  yeoman.generators.Base.apply(this, arguments);

  this.on('end', function () {
    this.installDependencies({ skipInstall: options['skip-install'] });
  });

  this.pkg = JSON.parse(this.readFileAsString(path.join(__dirname, '../package.json')));
};

util.inherits(AbbWpthemeGenerator, yeoman.generators.Base);

AbbWpthemeGenerator.prototype.askFor = function askFor() {
  var cb = this.async();

  // have Yeoman greet the user.
  console.log(this.yeoman);

  var prompts = [
    {
      name: 'themeTitle',
      message: 'What is the theme title?'
    },
    {
      name: 'authorName',
      message: 'What is the theme author\'s name?',
      default: 'Creative Precision'
    },
    {
      name: 'authorUrl',
      message: 'What is the theme Author\'s url?',
      default: 'http://creativeprecision.co'
    },
    {
      name: 'fnPrefix',
      message: 'What prefix should be used for custom function(s)? e.g. **_function',
      default: 'cp'
    },
    {
      name: 'textDomain',
      message: 'What text domain should be used for translation, e.g. _e("Example String", **)',
      default: 'cp'
    }
  ];

  this.prompt(prompts, function (props) {
    this.themeTitle = props.themeTitle;
    this.authorName = props.authorName;
    this.authorUrl = props.authorUrl;
    this.fnPrefix = props.fnPrefix;
    this.textDomain = props.textDomain;

    cb();
  }.bind(this));
};

AbbWpthemeGenerator.prototype.app = function app() {
  this.mkdir('assets');
  this.mkdir('assets/scss');
  this.mkdir('assets/css');
  this.mkdir('assets/images');
  this.mkdir('assets/fonts');
  this.mkdir('assets/js');
  this.mkdir('assets/js/lib');
  this.mkdir('assets/includes');
  this.mkdir('assets/includes/widgets');

  // Main Build files
  this.copy('_package.json', 'package.json');
  this.copy('_bower.json', 'bower.json');
  this.copy('Gruntfile.js', 'Gruntfile.js');

  // Stylesheet files
  this.copy('theme_files/assets/css/custom.css', 'assets/css/custom.css');
  this.copy('theme_files/assets/scss/reset.scss', 'assets/scss/reset.scss');
  this.copy('theme_files/assets/scss/vars.scss', 'assets/scss/vars.scss');
  this.copy('theme_files/assets/scss/mixins.scss', 'assets/scss/mixins.scss');
  this.copy('theme_files/assets/scss/base.scss', 'assets/scss/base.scss');
  this.template('theme_files/assets/scss/style.scss', 'assets/scss/style.scss');

  // Javascript files (site not build)
  this.copy('theme_files/assets/js/site.js', 'assets/js/site.js');
  this.template('theme_files/assets/js/theme-customizer.js', 'assets/js/theme-customizer.js');

  // Theme files
  this.copy('theme_files/header.php', 'header.php');
  this.copy('theme_files/footer.php', 'footer.php');
  this.template('theme_files/functions.php', 'functions.php');
  this.template('theme_files/index.php', 'index.php');
  this.template('theme_files/page.php', 'page.php');
  this.template('theme_files/single.php', 'single.php');
  this.template('theme_files/comments.php', 'comments.php');
  this.template('theme_files/template-example.php', 'template-example.php');
  this.template('theme_files/searchform.php', 'searchform.php');
  this.template('theme_files/assets/includes/custom-posttypes.php', 'assets/includes/custom-posttypes.php');
  this.template('theme_files/assets/includes/shortcodes.php', 'assets/includes/shortcodes.php');
  this.template('theme_files/assets/includes/ajax-frontend.php', 'assets/includes/ajax-frontend.php');
  this.template('theme_files/assets/includes/theme-customizer.php', 'assets/includes/theme-customizer.php');
  this.template('theme_files/assets/includes/widgets/widget-custom.php', 'assets/includes/widgets/widget-custom.php');

  // Extras
  this.template('theme_files/changelog.txt', 'changelog.txt');
  this.template('README.md', 'README.md');
};

AbbWpthemeGenerator.prototype.projectfiles = function projectfiles() {
  this.copy('editorconfig', '.editorconfig');
};

AbbWpthemeGenerator.prototype.runtime = function runtime() {
  this.copy('bowerrc', '.bowerrc');
}
