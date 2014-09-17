@ECHO OFF
set langs=en
set root=%CD%

if "%1" == "" (
	goto help
)

if "%1" == "help" (
	:help
	echo.Please use `make ^<target^>` where ^<target^> is one of
	echo.  html       to make standalone HTML files
	echo.  epub       to make an epub
	echo.  latex      to make LaTeX files, you can set PAPER=a4 or PAPER=letter
	goto end
)

set target=%1

(for %%l in (%langs%) do (
	set lang=%%l
	cd %lang%
	make %target% %lang%
	cd %root%
))

:end