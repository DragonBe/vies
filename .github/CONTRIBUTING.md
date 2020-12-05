## Contributing

First off, thank you for considering contributing to VIES. It's people
like you that make VIES such a great tool for anyone who needs to validate
VAT ID's within the European Union.

### 1. Where do I go from here?

If you've noticed a bug or have a question, [search the issue tracker](https://github.com/dragonbe/vies/issues?q=something)
to see if someone else in the community has already created a ticket.
If not, go ahead and [make one](https://github.com/dragonbe/vies/issues/new)!

### 2. Fork the project and create a branch

If this is something you think you can fix, then
[fork VIES](https://help.github.com/articles/fork-a-repo)
and create a branch with a descriptive name.

A good branch name would be (where issue #123 is the ticket you're working on):

```sh
git checkout -b 123-update-be-vat-numbers
```

### 3. Get the test suite running

Make sure you're using the most recent PHP version:

- 1.0 branch requires PHP 5.6.x or higher
- 2.0 branch requires PHP 7.1.x or higher

Now install PHP packages using [composer](https://getcomposer.org):

```sh
composer install
```

At this point you should be able to run the entire test suite using:

```sh
./vendor/bin/phpunit
```

### 4. Did you find a bug?

* **Ensure the bug was not already reported** by [searching all
  issues](https://github.com/dragonbe/vies/issues?q=).

* If you're unable to find an open issue addressing the problem, [open a new
  one](https://github.com/dragonbe/vies/issues/new).  Be sure to
  include a **title and clear description**, as much relevant information as
  possible, and a **code sample** or an **executable test case** demonstrating
  the expected behavior that is not occurring.

### 5. Implement your fix or feature

At this point, you're ready to make your changes! Feel free to ask for help;
everyone is a beginner at first :smile_cat:

### 6. Make a Pull Request

At this point, you should switch back to your master branch and make sure it's
up to date with VIES's master branch:

```sh
git remote add upstream git@github.com:dragonbe/vies.git
git checkout master
git pull upstream master
```

Then update your feature branch from your local copy of master, and push it!

```sh
git checkout 123-update-be-vat-numbers
git rebase master
git push --set-upstream origin 123-update-be-vat-numbers
```

Replace `123-update-be-vat-numbers` with the branch name you have given yourself.

Finally, go to GitHub and
[make a Pull Request](https://help.github.com/articles/creating-a-pull-request)
:D

Travis CI will run our test suite against all supported PHP versions. We care
about quality, so your PR won't be merged until all tests pass. It's unlikely,
but it's possible that your changes pass tests in one PHP version but fails in
another. In that case, you'll have to setup your development environment (as
explained in step 3) to use the problematic PHP version, and investigate
what's going on!

The [PHP containers on Docker HUB](https://hub.docker.com/_/php) might be 
convenient for this purpose. You might want to make use of them.

### 7. Keeping your Pull Request updated

If a maintainer asks you to "rebase" your PR, they're saying that a lot of code
has changed, and that you need to update your branch so it's easier to merge.

To learn more about rebasing in Git, there are a lot of
[good](http://git-scm.com/book/en/Git-Branching-Rebasing)
[resources](https://help.github.com/articles/interactive-rebase),
but here's the suggested workflow:

```sh
git checkout 123-update-be-vat-numbers
git pull --rebase upstream master
git push --force-with-lease 123-update-be-vat-numbers
```

### 8. Merging a PR (maintainers only)

A PR can only be merged into master by a maintainer if:

* It is passing CI.
* It has been approved by at least two maintainers. If it was a maintainer who
  opened the PR, only one extra approval is needed.
* It has no requested changes.
* It is up to date with current master.

Any maintainer is allowed to merge a PR if all of these conditions are
met.

### 9. Shipping a release (maintainers only)

Maintainers need to do the following to push out a release:

* Make sure all pull requests are in
* Create a stable branch for that release:

This example explains the process to tag a new version on the 2.0 branch, where
`upstream` references [dragonbe/vies](https://github.com/dragonbe/vies)and 2.0.8 
is our latest tag.

  ```sh
  git fetch upstream
  git checkout master
  git pull --rebase upstream master
  git checkout 2.0
  git merge master
  git tag -a 2.0.9
  git push upstream 2.0.9 
  ```

That's all there is to it.
