Results
=======
@see http://adriengibrat.github.com/benchmarking-dependency-injection-containers/

Usage
=====
```sh
composer update #update DI libraries in vendor directory
composer benchmark #run benchmark and save results in ./gh-pages/index.html
composer gh-pages #update gh-pages branch with latest result (gh-pages branch only contains index.html)
composer publish #run benchmark, update gh-pages branch and push everything back to github
```