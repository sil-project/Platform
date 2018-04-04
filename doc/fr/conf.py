import sys, os
from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer
import sphinx_rtd_theme


extensions = [
    'sphinx.ext.intersphinx',
    'sphinx.ext.autodoc',
    'sphinx.ext.todo',
    'sphinx.ext.coverage',
    'sphinx.ext.imgmath',
    'sphinx.ext.ifconfig',
    'sensio.sphinx.configurationblock'
]


templates_path = ['_templates']
source_suffix = ['.rst']
master_doc = 'index'

project = u'Sil & Blast Projects'
copyright = u'2018, Libre-Informatique'

version = ''
release = ''

exclude_patterns = ['_build', 'Thumbs.db', '.DS_Store']

pygments_style = 'sphinx'
todo_include_todos = True

html_theme = "sphinx_rtd_theme"
html_theme_path = [sphinx_rtd_theme.get_html_theme_path()]
html_static_path = ['_static']
htmlhelp_basename = 'sildoc'

man_pages = [
    ('index', 'Sil', u'Sil Documentation', u'Blast Documentation',
     [u'Libre Informatique'], 1)
]
sys.path.append(os.path.abspath('_exts'))
lexers['php'] = PhpLexer(startinline=True)
lexers['php-annotations'] = PhpLexer(startinline=True)
rst_epilog = """
"""

def setup(app):
    app.add_stylesheet('css/custom.css')
