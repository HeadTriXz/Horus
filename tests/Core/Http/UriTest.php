<?php

use Horus\Core\Http\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    private const TEST_URL = "https://joe:password@google.com:443/foo/bar?abc=123#section";

    public function testGetScheme()
    {
        $uri = new Uri(static::TEST_URL);
        $this->assertEquals("https", $uri->getScheme());
    }

    public function testWithScheme()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withScheme("http");

        $this->assertEquals("http", $uri->getScheme());
    }

    public function testWithSchemeRemovesSuffix()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withScheme("http://");

        $this->assertEquals("http", $uri->getScheme());
    }

    public function testWithSchemeEmpty()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withScheme("");

        $this->assertEquals("", $uri->getScheme());
    }

    public function testGetAuthorityWithUsernameAndPassword()
    {
        $uri = new Uri(static::TEST_URL);
        $this->assertEquals("joe:password@google.com", $uri->getAuthority());
    }

    public function testWithUserInfo()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withUserInfo("bob", "pass");

        $this->assertEquals("bob:pass", $uri->getUserInfo());
    }

    public function testWithUserInfoEncodesCorrectly()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withUserInfo("bob@example.com", "pass:word");

        $this->assertEquals("bob%40example.com:pass%3Aword", $uri->getUserInfo());
    }

    public function testWithUserInfoRemovesPassword()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withUserInfo("bob");

        $this->assertEquals("bob", $uri->getUserInfo());
    }

    public function testWithUserInfoRemovesInfo()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withUserInfo("bob", "password");
        $uri = $uri->withUserInfo("");

        $this->assertEquals("", $uri->getUserInfo());
    }

    public function testGetHost()
    {
        $uri = new Uri(static::TEST_URL);
        $this->assertEquals("google.com", $uri->getHost());
    }

    public function testWithHost()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withHost("facebook.com");
        $this->assertEquals("facebook.com", $uri->getHost());
    }

    public function testGetPortWithSchemeAndNonDefaultPort()
    {
        $uri = new Uri("https://www.google.com:4000");

        $this->assertEquals(4000, $uri->getPort());
    }

    public function testGetPortWithSchemeAndDefaultPort()
    {
        $uriHttp = new Uri("http://www.example.com:80");
        $uriHttps = new Uri("https://www.example.com:443");

        $this->assertNull($uriHttp->getPort());
        $this->assertNull($uriHttps->getPort());
    }

    public function testGetPortWithoutSchemeAndPort()
    {
        $uri = new Uri("www.example.com");

        $this->assertNull($uri->getPort());
    }

    public function testGetPortWithSchemeWithoutPort()
    {
        $uri = new Uri("http://www.example.com");

        $this->assertNull($uri->getPort());
    }

    public function testWithPort()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withPort(8000);

        $this->assertEquals(8000, $uri->getPort());
    }

    public function testWithPortNull()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withPort(null);

        $this->assertEquals(null, $uri->getPort());
    }

    public function testWithPortInvalidInt()
    {
        $this->expectException(InvalidArgumentException::class);

        $uri = (new Uri(static::TEST_URL))
            ->withPort(70000);
    }

    public function testGetPath()
    {
        $uri = new Uri(static::TEST_URL);
        $this->assertEquals("/foo/bar", $uri->getPath());
    }

    public function testWithPath()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withPath("/new");

        $this->assertEquals("/new", $uri->getPath());
    }

    public function testWithPathWithoutPrefix()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withPath("new");

        $this->assertEquals("new", $uri->getPath());
    }

    public function testWithPathEmptyValue()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withPath("");

        $this->assertEquals("", $uri->getPath());
    }

    public function testWithPathUrlEncodesInput()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withPath("/includes?/new");

        $this->assertEquals("/includes%3F/new", $uri->getPath());
    }

    public function testWithPathDoesNotDoubleEncodeInput()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withPath("/include%25s/new");

        $this->assertEquals("/include%25s/new", $uri->getPath());
    }

    public function testGetQuery()
    {
        $uri = new Uri(static::TEST_URL);
        $this->assertEquals("abc=123", $uri->getQuery());
    }

    public function testWithQuery()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withQuery("xyz=123");

        $this->assertEquals("xyz=123", $uri->getQuery());
    }

    public function testWithQueryRemovesPrefix()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withQuery("?xyz=123");

        $this->assertEquals("xyz=123", $uri->getQuery());
    }

    public function testWithQueryEmpty()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withQuery("");

        $this->assertEquals("", $uri->getQuery());
    }

    public function testFilterQuery()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withQuery("?foobar=%match");

        $this->assertEquals("foobar=%25match", $uri->getQuery());
    }

    public function testGetFragment()
    {
        $uri = new Uri(static::TEST_URL);
        $this->assertEquals("section", $uri->getFragment());
    }

    public function testWithFragment()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withFragment("other-fragment");

        $this->assertEquals("other-fragment", $uri->getFragment());
    }

    public function testWithFragmentRemovesPrefix()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withFragment("#other-fragment");

        $this->assertEquals("other-fragment", $uri->getFragment());
    }

    public function testWithFragmentEmpty()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withFragment("");

        $this->assertEquals("", $uri->getFragment());
    }

    public function testWithFragmentUrlEncode()
    {
        $uri = (new Uri(static::TEST_URL))
            ->withFragment("^a");

        $this->assertEquals("%5Ea", $uri->getFragment());
    }

    public function testToString()
    {
        $uri = new Uri(static::TEST_URL);

        $this->assertEquals("https://joe:password@google.com/foo/bar?abc=123#section", (string) $uri);

        $uri = $uri->withPath("bar");
        $this->assertEquals("https://joe:password@google.com/bar?abc=123#section", (string) $uri);

        $uri = $uri->withPath("/bar");
        $this->assertEquals("https://joe:password@google.com/bar?abc=123#section", (string) $uri);

        $uri = $uri->withScheme("")->withHost("")->withPort(null)->withUserInfo("")->withPath("//bar");
        $this->assertEquals("/bar?abc=123#section", (string) $uri);
    }
}
