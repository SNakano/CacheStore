guard 'phpunit', :tests_path => 'tests', :cli => '--colors' do
  # Watch tests files
  watch(%r{^tests/.+Test\.php$})

  # Watch library files and run their tests
  watch(%r{^src/Domino/CacheStore/(.+)\.php$}) { |m| "tests/Domino/CacheStore/Tests/#{m[1]}Test.php" }
end
